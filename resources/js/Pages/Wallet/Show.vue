<template>
    <breeze-authenticated-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ wallet.name }}
            </h2>
        </template>

        <div class="md:px-32 w-full">
            <div class="text-center space-x-4 my-4">
                <div class="field field-green">
                    <span>In:</span>
                    <span>
                        <Money :money="wallet.total_in"/>
                    </span>
                </div>
                <div class="field field-red">
                    <span>Out:</span>
                    <span>
                        <Money :money="wallet.total_out" />
                    </span>
                </div>
                <div class="field">
                    <span>Balance:</span>
                    <span
                        :class="{
                            'text-green-500': wallet.balance.amount >= 0,
                            'text-red-500': wallet.balance.amount < 0,
                        }"
                    >
                        <Money :money="wallet.balance"/>
                    </span>
                </div>
            </div>
            <div class="shadow overflow-hidden rounded border-b border-gray-200">
                <table class="table min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                    <tr>
                        <th></th>
                        <th>Id</th>
                        <th>Account</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Notes</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700">
                    <tr v-for="transaction in wallet.transactions" :key="transaction.id" class="odd:bg-gray-100">
                        <td class="w-8 px-1 text-center">
                            <span v-if="transaction.fraudulent" class="has-tooltip">
                                ❗
                                <span v-if="transaction.fraudulent" class="tooltip">
                                    Marked as fraudulent
                                </span>
                            </span>
                        </td>
                        <td>{{ transaction.operation_id }}</td>
                        <td>{{ transaction.other_wallet_id }}</td>
                        <td class="text-green-500">{{ transaction.debits }}</td>
                        <td class="text-red-500">{{ transaction.credits }}</td>
                        <td>{{ transaction.notes }}</td>
                        <td class="text-center">{{ formatDateTime(transaction.created_at) }}</td>
                        <td class="text-center">
                            <breeze-button class="px-2 py-1 w-24 justify-center" dusk="mark-button"
                                           @click="toggleMark(transaction.id)">
                                {{ transaction.fraudulent ? 'Unmark' : 'Mark' }}
                            </breeze-button>
                            <breeze-button class="px-2 py-1 w-24 justify-center" dusk="delete-button"
                                           @click="deleteTransaction(transaction.id)">Delete
                            </breeze-button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
import {DateTime} from 'luxon'
import Money from "@/Components/Money";

export default {
    name: "wallet-show",

    components: {
        Money,
        BreezeAuthenticatedLayout,
        BreezeButton,
    },

    props: ['wallet'],

    methods: {
        formatDateTime(timestamp) {
            return DateTime.fromISO(timestamp).toFormat('yyyy-mm-dd')
        },
        toggleMark(transactionId) {
            this.$inertia.post(`/transaction/${transactionId}/mark`)
        },
        deleteTransaction(transactionId) {
            this.$inertia.delete(`/transaction/${transactionId}`)
        },
    }
}
</script>

<style scoped>

</style>
