<template>

</template>

<script setup>
import axios from "axios";
import {ref, onMounted} from "vue";

const loading = ref(false);
const transactions = ref([]);

onMounted(() => {
    fetch();
})

function fetch() {
    const url = '/api/transactions';

    loading.value = true;
    axios.get(url)
    .then(({ data }) => {
        loading.value = false;
        transactions.value = data.transaction;
    })
    .catch(error => {
        loading.value = false;
    });
}
</script>
