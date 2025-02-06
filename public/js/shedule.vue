<template>
    <div class="container">
      <div class="row tabs">
        <span class="tab-title" :class="{ activeTab: selectedTab === tab}"
              v-for="(tab, index) in tabs"
              :key="index"
              @click="selectTab(tab)">
          {{ tab }}
        </span>
      </div>
  
  
      <div v-show="selectedTab === 'Чемпионат'">
        <team-champ-component :shiptables="shiptables" />
      </div>
      <div v-show="selectedTab === 'Кубок'">
        <team-cup-component :cups="cups" />
      </div>
      <div v-show="selectedTab === 'Еврокубки'">
        <team-eurocup-component :eurocups="eurocups" />
      </div>
  
    </div>
  </template>
  
  <script>
  import axios from 'axios'
  
  export default {
    name: "teams",
    data() {
      return {
        shiptables: [],
        cups: [],
        eurocups: [],
        selectedTab: ''
      };
    },
    computed: {
      teamCode() {
        return window.teamCode;
      },
      tabs() {
        let arrTabs = [];
        if(this.shiptables.length > 0) {
          arrTabs.push('Чемпионат');
          if(this.selectedTab == '') {
          //this.selectedTab = 'Чемпионат';
          }
        }
        if(this.cups.length > 0) {
          arrTabs.push('Кубок');
          if(this.selectedTab == ''){
            this.selectedTab = 'Кубок';
          }
        }
        console.log(this.eurocups.length)
        if(this.eurocups.length > 0) {
          arrTabs.push('Еврокубки');
          if(this.selectedTab == ''){
            this.selectedTab = 'Еврокубки';
          }
          console.log(this.selectedTab)
        }
        return arrTabs;
      }
    },
    methods: {
      selectTab: function(tab){
        this.selectedTab = tab
      }
    },
    async created () {
      await axios.get('/api/team/champ/' + teamCode).then((response) => {
        this.shiptables = response.data
      });
      await axios.get('/api/team/cup/' + teamCode).then((response) => {
        this.cups = response.data
      });
      await axios.get('/api/team/eurocup/' + teamCode).then((response) => {
        this.eurocups = response.data
      });
    }
  }
  </script>
  
  <style>
  .tabs {
    margin-bottom: 10px;
  }
  .tab-title {
    background: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    border-bottom: none;
    font-weight: bold;
    cursor: pointer;
    margin-right: 10px;
  }
  .activeTab {
    background-color: #993366;
    color: #fff;
  }
  .champTableTitle {
    background-color: #6fbd90;
    text-align: center;
    font-size: 15px;
    font-weight: bold;
  }
  .col-lg-1 {
    padding-left: 5px;
  }
  .match-score {
    text-align: center;
    color: #003399;
    font-weight: bold;
  }
  .match-teams {
    text-align: center;
    font-size: 14px;
    color: #128746;
  }
  .row {
    margin-left: 0;
    margin-right: 0;
  }
  .odd {
    padding-bottom: 10px;
  }
  </style>