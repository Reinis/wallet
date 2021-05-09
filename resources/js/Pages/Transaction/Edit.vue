<template>
    <div class="flex flex-col items-center">
        <breeze-validation-errors class="mt-4 p-2"/>

        <form class="w-1/3 mt-16" @submit.prevent="submit">
            <breeze-input id="transaction-id" v-model="form.id" type="hidden"></breeze-input>
            <div>
                <breeze-label for="source" value="Source"/>
                <select id="source" v-model="form.source" class="w-full rounded" name="source">
                    <option v-for="wallet in wallets" :key="wallet.id" :value="wallet.id">{{ wallet.name }}</option>
                </select>
            </div>

            <breeze-input id="to-wallet" v-model="form.toWallet" type="hidden"/>
            <div class="mt-4">
                <breeze-label :value="targetLabel" for="target"/>
                <breeze-input id="target" v-model="form.target" autocomplete="target" autofocus
                              class="mt-1 block w-full" list="targets"
                              required
                              type="text"
                />
                <datalist id="targets">
                    <option
                        v-for="(name, index) in allWallets"
                        :key="index"
                        :value="index"
                    >
                        {{ name }}
                    </option>
                </datalist>
            </div>

            <div class="mt-4">
                <breeze-label for="amount" value="Amount (¢)"/>
                <breeze-input id="amount" v-model="form.amount" class="mt-1 block w-full" type="text"/>
            </div>

            <div class="mt-4">
                <breeze-label for="notes" value="Notes"/>
                <breeze-input id="notes" v-model="form.notes" class="mt-1 block w-full" type="text"/>
            </div>

            <div class="flex items-center justify-around mt-4">
                <inertia-link :href="route('dashboard')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Cancel
                </inertia-link>

                <breeze-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ml-4">
                    Submit
                </breeze-button>
            </div>
        </form>
    </div>
</template>

<script>
import {Link as InertiaLink, useForm} from '@inertiajs/inertia-vue3';
import Authenticated from "@/Layouts/Authenticated";
import BreezeButton from "@/Components/Button";
import BreezeInput from "@/Components/Input";
import BreezeLabel from "@/Components/Label";
import BreezeValidationErrors from "@/Components/ValidationErrors";

export default {
    name: "edit-transaction",

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
        flash: Object,
        wallets: Array,
        allWallets: Object,
        transaction: Object,
    },

    data() {
        return {
            form: useForm({
                id: this.transaction?.id ?? 0,
                source: this.transaction?.source ?? 0,
                target: this.transaction?.target ?? '',
                toWallet: this.transaction?.toWallet ?? false,
                amount: this.transaction?.amount ?? 0,
                currency: 'EUR',
                notes: this.transaction?.notes ?? '',
            })
        }
    },

    computed: {
        toWallet() {
            const isNumber = !isNaN(this.form.target) && !isNaN(parseInt(this.form.target));
            this.form.toWallet = isNumber
            return isNumber
        },
        targetLabel() {
            return this.toWallet ? `Target: ${this.allWallets[this.form.target]}` : 'Target'
        },
    },

    methods: {
        submit() {
            this.form.post(this.route('transaction.save'))
        },
    }
}
</script>

<style scoped>

</style>
