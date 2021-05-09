<template>
    <div class="md:px-32 py-8 w-full">
        <div class="text-center mb-4">
            <span class="pr-2 font-bold">In:</span>
            <span class="pr-4 text-green-500">
                {{ totalIn.formatted }}
            </span>
            <span class="pr-2 font-bold">Out:</span>
            <span class="pr-4 text-red-500">
                {{ totalOut.formatted }}
            </span>
            <span class="pr-2 font-bold">Balance:</span>
            <span :class="{'text-green-500': balance.amount >= 0, 'text-red-500': balance.amount < 0}">
                {{ balance.formatted }}
            </span>
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
                    <td class="w-8 text-center">{{ transaction.fraudulent ? '❗' : '' }}</td>
                    <td>{{ transaction.operation_id }}</td>
                    <td>{{ transaction.other_wallet_id ?? transaction.other }}</td>
                    <td class="text-green-500">{{ transaction.debit?.amount }}</td>
                    <td class="text-red-500">{{ transaction.credit?.amount }}</td>
                    <td>{{ transaction.notes }}</td>
                    <td class="text-center">{{ formatDateTime(transaction.created_at) }}</td>
                    <td class="text-center">
                        <form @submit.prevent="submit">
                            <breeze-button class="px-2 py-1 w-24 justify-center" @click="toggleMark(transaction.id)">
                                {{ transaction.fraudulent ? 'Unmark' : 'Mark' }}
                            </breeze-button>
                            <breeze-button class="px-2 py-1 w-24 justify-center"
                                           @click="deleteTransaction(transaction.id)">Delete
                            </breeze-button>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated'
import BreezeButton from '@/Components/Button'
import {DateTime} from 'luxon'

export default {
    name: "wallet-show",

    layout: Authenticated,

    components: {
        BreezeButton,
    },

    props: ['totalIn', 'totalOut', 'balance', 'wallet'],

    methods: {
        formatDateTime(timestamp) {
            return DateTime.fromISO(timestamp).toFormat('yyyy-mm-dd')
        },
        toggleMark(transactionId) {
            this.$inertia.post(`/transaction/${transactionId}/mark`)
        },
        deleteTransaction(transactionId) {
            this.$inertia.post(`/transaction/${transactionId}/delete`)
        },
    }
}
</script>

<style scoped>

</style>
