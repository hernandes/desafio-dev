<template>
    <div class="transactions__list" v-if="!loading && stores.length > 0">
        <h1>Transações</h1>
        <div class="transactions__list-store" v-for="store in stores">
            <h3>{{ store.store.name }} ({{ store.store.owner }})</h3>
            <table class="transactions__table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>CPF</th>
                        <th>Cartão</th>
                        <th class="text__right">Valor</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="transaction in store.transactions">
                        <td>{{ transaction.type_name }}</td>
                        <td>{{ to_datetime(transaction.transaction_at) }}</td>
                        <td>{{ transaction.document }}</td>
                        <td>{{ transaction.card }}</td>
                        <td class="text__right" :class="{'color__danger': transaction.value < 0, 'color__success': transaction.value > 0}">
                            {{ to_currency(transaction.value) }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" class="text__right"><strong>Total</strong></td>
                        <td class="text__right" :class="{'color__danger': store.sum < 0, 'color__success': store.sum > 0}">
                            <strong>{{ to_currency(store.sum) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script setup>
import axios from "axios";
import {ref, onMounted} from "vue";
import currency from "currency.js";
import moment from "moment";

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

function to_datetime(value) {
    return moment(value).format("DD/MM/YYYY HH:mm");
}

function to_currency(value) {
    return currency(value, { separator: ".", decimal: ",", symbol: "" }).format();
}

defineExpose({
    fetch
})
</script>
