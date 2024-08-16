<script>
    export default {
      props: [
          'predictions',
          'leagueWeekCount'
      ],

      created() {
        console.log(this.predictions)
      }
    }
</script>

<template>
    <div class="mainColumn">
        <div class="tableTitle">{{ this.leagueWeekCount }}. Week Predictions of Championship</div>
        <ul class="mainScreenContent">
            <li v-for="prediction in this.predictions">
                <div>{{ prediction.name }}</div>
                <div>%{{ prediction.win_rate }}</div>
            </li>
        </ul>
    </div>
</template>

<style scoped>

</style>