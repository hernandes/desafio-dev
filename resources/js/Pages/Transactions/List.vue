<template>
    <div class="stores">
        <div class="store" v-for="store in stores">
            <h1>{{ store.store.name }} ({{ store.store.owner }})</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>CPF</th>
                        <th>Cartão</th>
                        <th>Valor</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="transaction in store.transactions">
                        <td>{{ transaction.type_name }}</td>
                        <td>{{ transaction.transaction_at }}</td>
                        <td>{{ transaction.document }}</td>
                        <td>{{ transaction.card }}</td>
                        <td>{{ transaction.value }}</td>
                    </tr>

                    <tr>
                        <td colspan="4">Total</td>
                        <td>{{ store.sum }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script setup>
import axios from "axios";
import {ref, onMounted} from "vue";

const loading = ref(false);
const stores = ref([]);

onMounted(() => {
    fetch();
})

function fetch() {
    const url = '/api/transactions';

    loading.value = true;
    axios.get(url)
        .then(({ data }) => {
            loading.value = false;
            stores.value = data.stores;
        })
        .catch(error => {
            loading.value = false;
        });
}
</script>
