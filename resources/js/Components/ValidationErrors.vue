<template>
    <div v-if="hasErrors" class="mx-auto sm:px-6 lg:px-8 bg-red-600 rounded">
        <div class="font-medium">Whoops! Something went wrong.</div>

        <ul class="mt-3 list-disc list-inside text-sm">
            <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        computed: {
            errors() {
                return this.$page.props.errors
            },

            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        }
    }
</script>
