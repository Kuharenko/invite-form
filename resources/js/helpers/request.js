import {ref} from "vue";

export async function useRequest(url, method = 'GET', body = {}) {
    const data = ref(null)
    const error = ref(null)

    const postOptions = method !== 'GET' ? {
        method, body: JSON.stringify(body), headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    } : {};

    const response = await fetch(url, postOptions);

    if (response.status < 400) {
        data.value = await response.json()
    } else {
        error.value = await response.json()
    }

    return {data, error}
}