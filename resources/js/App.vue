<script setup>
import {ref} from "vue";
import InviteForm from "./components/forms/InviteForm.vue";

const isOpen = ref(false);
const formResult = ref(null);

const submit = (result) => {
    isOpen.value = false;
    formResult.value = result.message
}

const toggleForm = () => {
    isOpen.value = !isOpen.value;
    formResult.value = null
}

</script>

<template>

    <button class="btn btn-add" @click="toggleForm">Invite</button>
    <InviteForm @close="toggleForm" @submit="submit" v-show="isOpen"></InviteForm>


    <br>
  {{ formResult }}

</template>

<style lang="scss">
@import url("/resources/sass/app.scss");
</style>