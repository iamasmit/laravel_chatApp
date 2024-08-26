<script setup>
import Button from "primevue/button";

import InputText from "primevue/inputtext";
</script>

<template>
    <main>
        <h1 class="text-2xl text-red-500">Hello World</h1>
        <Button label="Submit" />
        <InputText type="text" v-model="value" />
    </main>
</template>
