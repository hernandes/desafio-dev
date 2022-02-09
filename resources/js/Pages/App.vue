<template>
    <div class="transactions">
        <TransactionImport @imported="onImport" />
        <Transactions ref="transactions" />
    </div>
</template>

<script setup>
import Transactions from "./Transactions/List";
import TransactionImport from "./Transactions/Import";
import {ref} from "vue";

const transactions = ref(null);

function onImport() {
    transactions.value.fetch();
}
</script>
