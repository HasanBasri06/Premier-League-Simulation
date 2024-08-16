<script>


    export default {
        props: [
            'teams'
        ],
    }
</script>

<template>
    <div class="mainColumn">
        <div class="tableTitle">League Table</div>
        <div class="mainScreenContent">
            <table>
                <tr>
                    <td width="20px">Teams</td>
                    <td width="20px">PTS</td>
                    <td width="20px">P</td>
                    <td width="20px">W</td>
                    <td width="20px">D</td>
                    <td width="20px">L</td>
                    <td width="20px">GD</td>
                </tr>
                <tr v-for="(team, index) in teams" :key="index">
                    <td>
                        <div>{{ team.name }} <img :src="'http://127.0.0.1:8000' + team.image" width="20px" /></div>
                        
                    </td>
                    <td>{{ team.team_power.PTS }}</td>
                    <td>{{ team.team_power.P }}</td>
                    <td>{{ team.team_power.W }}</td>
                    <td>{{ team.team_power.D }}</td>
                    <td>{{ team.team_power.L }}</td>
                    <td>{{ team.team_power.GD }}</td>
                </tr>
            </table>                    
        </div>
    </div>
</template>

<style scoped>
    img {
        object-fit: cover;
    }
</style>