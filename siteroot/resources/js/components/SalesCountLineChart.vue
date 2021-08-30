<script>
import { Line } from 'vue-chartjs'
export default {
  extends: Line,
    props: {
      dateRange: {
        type: Array,
        default: ['2020-06-01', '2020-06-30']
      }
    },
    data () {
      return {
            loaded: false,
            chartdata: {},
            options: {}
        }
    },
    watch: {
      dateRange: function (val) {
          if (val[0] != null &&  val[1] != null) {
              this.fetchDateCount();
          }
      }
    },
    mounted () {
      this.fetchDateCount();
    },
    methods: {
      fetchDateCount: function () {
              const token = localStorage.getItem('user-token')
              if (token) {
                  axios.defaults.headers.common['Authorization'] = 'Bearer ' +token
              } else {
                  return;
              }

              axios.get('api/sales/dateCount?start='+this.dateRange[0]+'&end='+this.dateRange[1]).then(response => {
                  let labels = [];
                  let counts = [];
                  $.each(response.data, function(key, value) {
                          labels.push(value.date);
                          counts.push(value.count);
                      });
                      this.chartdata = {
                          labels: labels,
                          datasets: [{
                              label: 'Daily sales',
                              backgroundColor: '#f87979',
                              data:counts
                          }]
                      };
                  this.options =  {
                      responsive: true,
                      maintainAspectRatio: false,
                      title: {
                        display: true,
                        text: "Sales Data"
                      },
                    scales: {
                        yAxes: [{
                          ticks: {
                            suggestedMin: 0
                          }
                        }]
                      }
                    }
                  this.renderChart(this.chartdata, this.options)
              }).catch(error => console.log(error));
      }
    }
}
</script>