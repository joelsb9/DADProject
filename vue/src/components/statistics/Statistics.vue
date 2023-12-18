<template>
    <div>
      <div>
        <canvas id="transactionsPerCategory"></canvas>
      </div>
      <div>
        <canvas id="valueOfTransactionsPerCategory"></canvas>
      </div>
      <div>
        <canvas id="transactionsPerDay"></canvas>
      </div>
      <div>
        <canvas id="transactionsPerMonth"></canvas>
      </div>
      <div>
        <canvas id="transactionsPerYear"></canvas>
      </div>
    </div>
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs'
  import axios from 'axios'
  
  export default {
    components: {
      BarChart: Bar
    },
    data() {
      return {
        transactionsPerCategory: null,
        valueOfTransactionsPerCategory: null,
        transactionsPerDay: null,
        transactionsPerMonth: null,
        transactionsPerYear: null
      }
    },
    methods: {
      async fetchData() {
        this.transactionsPerCategory = await axios.get('/api/transactionsPerCategory')
        this.valueOfTransactionsPerCategory = await axios.get('/api/valueOfTransactionsPerCategory')
        this.transactionsPerDay = await axios.get('/api/transactionsPerDay')
        this.transactionsPerMonth = await axios.get('/api/transactionsPerMonth')
        this.transactionsPerYear = await axios.get('/api/transactionsPerYear')
      },
      createCharts() {
        this.$refs.transactionsPerCategory.renderChart(this.transactionsPerCategory.data)
        this.$refs.valueOfTransactionsPerCategory.renderChart(this.valueOfTransactionsPerCategory.data)
        this.$refs.transactionsPerDay.renderChart(this.transactionsPerDay.data)
        this.$refs.transactionsPerMonth.renderChart(this.transactionsPerMonth.data)
        this.$refs.transactionsPerYear.renderChart(this.transactionsPerYear.data)
      }
    },
    async mounted() {
      await this.fetchData()
      this.createCharts()
    }
  }
  </script>





<!-- 
  <script>
  import axios from 'axios';
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, BarController, PieController, ArcElement } from 'chart.js';
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, BarController, PieController, ArcElement);
  
  export default {
    data() {
      return {
        transactionsPerMonth: [],
        transactionsPerType: [],
        barChart: null,
        pieChart: null
      }
    },
    methods: {
      getMonthName(monthNumber) {
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return monthNames[monthNumber - 1];
      }
    },
    
    async created() {
      const response1 = await axios.get('/statistics/transactionsPerMonth');
      this.transactionsPerMonth = response1.data;
  
      const response2 = await axios.get('/statistics/transactionsPerType');
      this.transactionsPerType = response2.data;
  
      this.barChart = new ChartJS(document.getElementById('myBarChart'), {
        type: 'bar',
        data: {
          labels: this.transactionsPerMonth.map(stat => this.getMonthName(stat.month)),
          datasets: [{
            label: 'Transactions per month',
            data: this.transactionsPerMonth.map(stat => stat.count),
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            },
            x: {
              position: 'bottom'
            }
          }
        }
      });
  
      this.pieChart = new ChartJS(document.getElementById('myPieChart'), {
        type: 'pie',
        data: {
          labels: this.transactionsPerType.map(stat => stat.payment_type),
          datasets: [{
            label: 'Transactions per type',
            data: this.transactionsPerType.map(stat => stat.count),
            backgroundColor: [
            'rgb(0, 102, 255)',   
            'rgb(0, 204, 0)',   
            'rgba(255, 0, 0, 08)',   
            'rgb(255, 153, 0)', 
            'rgb(102, 204, 255)',
            'rgb(255, 255, 0)'  
          ],
          borderColor: [
            'rgba(0, 0, 255, 1)',     
            'rgba(0, 128, 0, 1)',     
            'rgba(237, 41	, 57)',     
            'rgba(255, 165, 0, 1)',   
            'rgb(102, 204, 255)',     
            'rgb(255, 255, 0)'   
  ],
  borderWidth: 1
          }]
        }
      });
    }
  }
  </script>

<template>
    <div class="d-flex justify-content-between">
      <div class="mx-2">
        <h3 class="mt-4">Statistics</h3>
        
        <hr>
      </div>
      
      </div>
      <br>
      <br>
      <div style="display: flex; justify-content: space-around;">
        <div style="width: 700px; height: 700px;">
          <canvas id="myBarChart"></canvas>
        </div>
        <div style="width: 400px; height: 400px;">
          <canvas id="myPieChart"></canvas>
        </div>
      </div>
    
  </template>
   -->