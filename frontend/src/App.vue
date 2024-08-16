<script>
    import axios from 'axios';
import { ref } from 'vue';
import './App.css';
import LeagueTeams from './components/LeagueTeams.vue';
import Predictions from './components/Predictions.vue';
import Result from './components/Result.vue';
import TeamModal from './components/TeamModal.vue';

    export default {
        components: {
            TeamModal,
            LeagueTeams,
            Result,
            Predictions
        },

        setup() {
            let teams = ref([]);
            let result = ref([])
            let matchResultDate = ref(new Date())
            let predictionsDate = ref(new Date())
            let predictions = ref([])
            let leagueWeekCount = ref(1)
            let alreadyExistStatus = ref(true)

            return {
                teams,
                result,
                matchResultDate,
                predictions,
                predictionsDate,
                leagueWeekCount,
                alreadyExistStatus
            }
        },

        methods: {
            async getParseLeagueTeams() {
              try {
                const response = await axios.get('http://127.0.0.1:8000/api/parse-league-teams');
                this.teams = await response.data.data
              } catch (error) {
                throw new Error("Lig başlatmada bir sorun oluştu");
              }
            },

            async getWeekResult(date = null, incrementLeagueCount = false) {
              try {
                let query = ''
                if (date !== null) {
                  query = '?startDate='+date
                }

                if (incrementLeagueCount) {
                  this.leagueWeekCount++
                }

                const response = await axios.get('http://127.0.0.1:8000/api/week-result'+query)
                this.result = await response.data.data

              } catch (error) {
                this.alreadyExistStatus = false 

                throw new Error('Haftalık sonuçlar yüklenirken hata oluştu.')
              }
            },

            async getPredictionOfWeek(date = null) {
              try {
                let query = ''
                if (date !== null) {
                  query = '?startDate='+date
                }

                const response = await axios.get("http://127.0.0.1:8000/api/prediction-of-week"+query);
                this.predictions = await response.data.data
              }catch (e) {
                throw new Error("tahminler getirilirken bir sorun oluştu" + e)
              }
            },

            async startPremierLeague() {
                await this.getParseLeagueTeams()
                await this.getWeekResult()
                await this.getPredictionOfWeek()
                this.alreadyExistStatus = true
            },

          async handleNextWeek() {
              let week = this.handleAddThisWeek(this.matchResultDate)
              let formatDate = this.formatDate(week)
              await this.getWeekResult(formatDate, true);

              let predictionWeek = this.handleAddThisWeek(this.predictionsDate)
              let predictionFormatDate = this.formatDate(predictionWeek)
              await this.getPredictionOfWeek(predictionFormatDate)
          },

          handleAddThisWeek(week) {
              week.setDate(week.getDate() + 7)

              return week
          },

          formatDate(date) {
              const year = date.getFullYear()
              const month = (date.getMonth() + 1).toString().padStart(2, '0')
              const day = date.getDate().toString().padStart(2, '0')

              return year+"-"+month+"-"+day
          },

          async alreadyExistData() {
            try {
              const response = await axios.get('http://127.0.0.1:8000/api/already-exist-data')

              if (!response.data.status) {
                this.alreadyExistStatus = false
              }

              if (response.status) {
                this.result = response.data.data.result
                this.teams = response.data.data.teams
                this.predictions = response.data.data.predictions
              }
            } catch (e) {
              throw new Error('Bilgiler alınırken beklenmedik bir hata oluştu')
            }
          }
        },

      async mounted() {
        await this.alreadyExistData()
      }
    }
</script>

<template>
    <TeamModal>
        <template v-slot:teams>
            <LeagueTeams :teams="teams" />
        </template>
        <template v-slot:result>
            <Result :result="result" />
        </template>
        <template v-slot:predictions>
            <Predictions :predictions="predictions" :leagueWeekCount="leagueWeekCount" />
        </template>
        <template v-slot:controller>
            <div class="mainController">
                <button
                    id="playAllBtn"
                    @click="startPremierLeague"
                    :disabled="alreadyExistStatus"
                    :class="{'disabledButton': alreadyExistStatus}"
                >Play All</button>
                <div class="calendarController">
                    <button
                        @click="handleNextWeek"
                        :disabled="!alreadyExistStatus"
                        :class="{'disabledButton': !alreadyExistStatus}"
                    >Next Week</button>
                </div>
            </div>
        </template>
    </TeamModal>
</template>

<style scoped>
  .disabledButton {
    cursor: not-allowed!important;
    opacity: 0.5!important;
    background-color: gray;
  }
</style>