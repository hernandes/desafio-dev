<template>
    <div class="transactions__import">
        <h1>Importar arquivo CNAB</h1>

        <form @submit.prevent="onSend" class="form">
            <div class="form__group form__required">
                <label for="file" class="form__label">Arquivo</label>
                <input type="file" @change="onFile" id="file" required class="form__input" />
            </div>

            <div class="form__submit">
                <button type="submit" :disabled="loading" class="button button__primary">Importar</button>
            </div>
        </form>
    </div>
</template>

<script setup>
import axios from "axios";
import {ref, reactive} from "vue";

const loading = ref(false);
const form = reactive({
    file: null
});
const emit = defineEmits(["imported"]);

function onSend() {
    loading.value = true;

    const url = "/api/transactions/import";
    const data = new FormData();
    data.append("file", form.file);
    axios.post(url, data)
    .then(({ data }) => {
        loading.value = false
        emit("imported", data);
    })
    .catch(error => {
        loading.value = false;
        alert(error);
    })
}

function onFile(e) {
    form.file = e.target.files[0];
}
</script>
