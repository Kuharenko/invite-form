<script setup>
import {defineProps, defineEmits, ref, onMounted} from 'vue'
import {faker} from "@faker-js/faker";
import {useRequest} from "../../helpers/request";

const {isOpen} = defineProps({
    isOpen: Boolean
})

const emit = defineEmits(['close', 'submit'])

const newEmail = ref(null);
const newEmailIsInvalid = ref(false);
const userPermissions = ref([]);
const userErrors = ref([]);
const personalMessage = ref(null);
const toggleDropdown = ref(null);

const recipients = ref([
    {email: "test2@mail.com", full_name: null, user_permission_id: 2},
    {email: "test1@mail.com", full_name: 'John Wick', user_permission_id: 1},
    {email: "test1@mail.com", full_name: 'John Wick', user_permission_id: 1},
    {email: "test1@mail.com", full_name: 'John Wick', user_permission_id: 1},
    {email: "test2@mail.com", full_name: null, user_permission_id: 2},
    {email: "test1@mail.com", full_name: 'John Wick', user_permission_id: 1},
    {email: "test2@mail.com", full_name: null, user_permission_id: 2},
]);

onMounted(async () => {
    const {data} = await useRequest('/api/user-permissions')
    userPermissions.value = data.value.data;
})

const add = () => {
    newEmailIsInvalid.value = false
    if (newEmail.value) {
        const isValidEmail = validateEmail();
        console.log('isValidEmail', isValidEmail)
        if (!isValidEmail) {
            newEmailIsInvalid.value = true
            return
        }
        const needCreateFullName = Math.random() < 0.5

        recipients.value.push({
            email: newEmail.value,
            full_name: needCreateFullName ? faker.name.fullName() : null,
            user_permission_id: 1
        })

        newEmail.value = null;
    }
}

const validateEmail = () => {
    console.log('newEmail.value', newEmail.value)
    if (newEmail.value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
        console.log('here true')
        return true
    } else {
        console.log('here false')
        return false;
    }
}

const getUserPermissionName = (id) => {
    return userPermissions.value.find(item => item.id === id)?.name ?? '-'
}

const getFormErrors = (key, field = 'email') => {
    let message = null;

    if (userErrors.value.errors) {
        Object.keys(userErrors.value.errors).forEach((errorKey) => {
            if (errorKey.includes(`${key}.${field}`)) {
                message = String(userErrors.value.errors[errorKey][0]).replace(errorKey, field)
            }
        })
    }

    return message
}

const remove = (key) => {
    recipients.value.splice(key, 1)
}

const send = async () => {
    const params = {
        recipients: recipients.value,
        message: personalMessage.value
    }

    const {data, error} = await useRequest('/api/invite', 'POST', params)
    if (error.value) {
        userErrors.value = error.value
    } else {
        emit('submit', data.value);
    }
}

const choosePermission = (key, value) => {
    recipients.value[key].user_permission_id = value
    toggleDropdown.value = null
}

const openDropdown = (key) => {
    if (toggleDropdown.value) {
        toggleDropdown.value = null
    } else {
        toggleDropdown.value = key
    }
}

</script>

<template>
    <div v-show="isOpen" class="modal">
        <div class="invite-form">
            <div class="head">
                <div class="title">Invite others</div>
                <button class="close-btn" @click="$emit('close')"></button>
            </div>
            <div class="body">
                <div class="manual-invite">
                    <input class="input new-email" :class="newEmailIsInvalid ? 'input-error' : ''"
                           placeholder="Enter people E-mails" type="text" v-model="newEmail" @keydown.enter="add">
                    <button class="btn btn-add" :class="newEmail ? '' : 'disabled'" @click="add">Add</button>
                </div>
                <div class="social-invite">
                    <div class="tip">
                        or add from
                    </div>
                    <div>
                        <button class="btn social soc1"></button>
                        <button class="btn social soc2"></button>
                        <button class="btn social soc3"></button>
                        <button class="btn social soc4"></button>
                    </div>
                </div>
            </div>
            <div class="preview">
                <div class="list">
                    <div v-for="(recipient, key) in recipients" :key="key" class="panel">
                        <template v-if="recipient.full_name">
                            <div class="list-item">
                                <div class="group">
                                    <div class="primary-row">{{ recipient.full_name }}</div>
                                    <div class="secondary-row" :class="getFormErrors(key) ? 'error' : ''">
                                        {{ recipient.email }}

                                        {{ getFormErrors(key) }}
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="btn" @click="openDropdown(key)">
                                        {{ getUserPermissionName(recipient.user_permission_id) }}
                                    </button>
                                </div>
                                <div class="dropdown-body" v-show="toggleDropdown === key">
                                    <div v-for="permission in userPermissions">
                                        <button class="btn select-btn" @click="choosePermission(key, permission.id)">
                                            <div class="active-mark">
                                                <div class="active-mark-icon"
                                                     v-show="permission.id ===recipient.user_permission_id">
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="title">{{ permission.name }}</div>
                                                <div class="description">{{ permission.description }}</div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="list-item">
                                <div class="primary-row" :class="getFormErrors(key) ? 'error' : ''">
                                    {{ recipient.email }}

                                    {{ getFormErrors(key) }}
                                </div>
                                <div class="dropdown">
                                    <button class="btn" @click="openDropdown(key)">
                                        {{ getUserPermissionName(recipient.user_permission_id) }}
                                    </button>
                                </div>
                                <div class="dropdown-body" v-show="toggleDropdown === key">
                                    <div v-for="permission in userPermissions">
                                        <button class="btn select-btn" @click="choosePermission(key, permission.id)">
                                            <div class="active-mark">
                                                <div class="active-mark-icon"
                                                     v-show="permission.id ===recipient.user_permission_id">
                                                </div>
                                            </div>
                                            <div class="info">
                                                <div class="title">{{ permission.name }}</div>
                                                <div class="description">{{ permission.description }}</div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="remove">
                            <button class="btn remove-btn" @click="remove(key)"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <input type="text" class="input" v-model="personalMessage" placeholder="Personal message (optional)">


                <div class="actions">
                    <span>{{ recipients.length }} recipients</span>
                    <button class="btn btn-send" @click="send">Send</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
@import url("/resources/sass/invite-form.scss");
</style>