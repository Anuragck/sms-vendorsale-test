<script>
import { Pie } from "vue-chartjs";

export default {
  extends: Pie,
  props: ["inputlabel", "inputdata"],
  data() {
    return {
      chartData: {


        labels: this.inputlabel,

        datasets: [
          {
            borderWidth: 1,
            borderColor: [
              "rgba(255,99,132,1)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 1)",
              "rgba(75, 192, 192, 1)",
            ],
            backgroundColor: [
              "rgba(255, 99, 132, 0.2)",
              "rgba(54, 162, 235, 1)",
              "rgba(255, 206, 86, 0.2)",
              "rgba(75, 192, 192, 0.2)",
            ],

            data: this.inputdata,
          },
        ],
      },
      options: {
        legend: {
          display: true,
            position:'bottom',
        },
        responsive: true,
        maintainAspectRatio: false,

      },
    };
  },
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
  watch: {
    inputlabel: function () {
      this.chartData.labels = this.inputlabel;
      this.chartData.datasets[0].data = this.inputdata;
      this.renderChart(this.chartData, this.options);
    },
  },
};
</script>

