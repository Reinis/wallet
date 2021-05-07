<template>
    <div class="flex justify-center">
        <breeze-validation-errors class="mb-4"/>

        <form class="w-1/3 mt-16" @submit.prevent="submit">
            <breeze-input id="wallet-id" v-model="form.id" type="hidden"></breeze-input>
            <div>
                <breeze-label for="name" value="Name"/>
                <breeze-input id="name" v-model="form.name" autocomplete="name" autofocus class="mt-1 block w-full"
                              required
                              type="text"/>
            </div>

            <div class="mt-4">
                <breeze-label for="description" value="Description"/>
                <breeze-input id="description" v-model="form.description" class="mt-1 block w-full" type="text"/>
            </div>

            <div class="flex items-center justify-around mt-4">
                <inertia-link :href="route('dashboard')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Cancel
                </inertia-link>

                <breeze-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ml-4">
                    Save
                </breeze-button>

                <breeze-button v-if="wallet?.id" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                               class="ml-4 bg-red-600" @click.prevent="deleteWallet">
                    Delete
                </breeze-button>
            </div>
        </form>
    </div>
</template>

<script>
import {Link as InertiaLink} from '@inertiajs/inertia-vue3'
import Authenticated from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
import BreezeInput from '@/Components/Input'
import BreezeLabel from '@/Components/Label'
import BreezeValidationErrors from '@/Components/ValidationErrors'

export default {
    name: "edit-wallet",

    layout: Authenticated,

    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        InertiaLink,
    },

    props: {
        auth: Object,
        errors: Object,
        wallet: Object,
    },

    data() {
        return {
            form: this.$inertia.form({
                id: this.wallet?.id ?? 0,
                name: this.wallet?.name ?? '',
                description: this.wallet?.description ?? '',
            })
        }
    },

    methods: {
        submit() {
            this.form.post(this.route('wallet.save'))
        },
        deleteWallet() {
            this.form.post(this.route('wallet.delete', {wallet: this.wallet.id}))
        }
    }
}
</script>

<style scoped>

</style>
