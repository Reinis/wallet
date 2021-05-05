<template>
    <div class="flex gap-4 flex-wrap justify-center">
        <wallet-item v-for="wallet in wallets" :key="wallet.id" :wallet="wallet"></wallet-item>
    </div>
</template>

<script>
import WalletItem from "@/Components/WalletItem"

export default {
    name: "wallet-cards",
    components: {
        WalletItem
    },
    props: ['wallets'],
}
</script>

<style scoped>

</style>
