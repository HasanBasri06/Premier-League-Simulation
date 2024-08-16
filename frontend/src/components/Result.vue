<script>
    export default {
        props: [
            'result',
        ],

    }
</script>

<template>
    <div class="mainColumn">
        <div class="tableTitle">Match Results</div>
        <div class="mainScreenContent">
            <div class="weekMatchRow" v-for="result in this.result">
                <div class="alignRow">
                  <label>{{result.team_one.name}}</label>
                  <img :src="'http://127.0.0.1:8000' + result.team_one.image" width="20px" />
                </div>
                <div>{{result.team_one.goal}} - {{result.team_two.goal}}</div>
                <div class="alignRow">
                  <img :src="'http://127.0.0.1:8000' + result.team_two.image" width="20px" />
                  <label>{{result.team_two.name}}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .weekMatchRow {
        width: 100%;
        height: 40px;
        display: flex;
        align-items: center;
        font-weight: 600;
        justify-content: space-between;
        background-color: white;
    }

    .alignRow {
      display: flex;
      align-items: center;
      gap: 0 7px;
    }
</style>