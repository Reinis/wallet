<template>
{{ formattedMoney }}
</template>

<script>
export default {
    name: "Money",
    props: ['money'],
    computed: {
        formattedMoney() {
            return new Intl.NumberFormat('en-US', {style: 'currency', currency: this.money.currency})
                .format(Number(this.money.amount)/100)
        }
    }
}
</script>

<style scoped>

</style>
