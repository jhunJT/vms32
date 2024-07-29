const ctx = document.getElementById('piestat1');

var totalRVR = ((totalRV-totalCV)/totalRV)*100;
var totalCVR = (totalCV/totalRV)*100;

var roundedRV = totalRVR.toFixed(2);
var roundedCV = totalCVR.toFixed(2);

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['UNSURVEYED','CV' ],
    datasets: [{
      label: '# of Votes',
      data: [roundedRV,roundedCV],
      borderWidth: 2,
      backgroundColor: ["#f32f53", "#0f9cf3"],
      hoverBackgroundColor: ["#1cbb8c", "#ebeff2"],
      hoverBorderColor: "#fff"
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

const ctx1 = document.getElementById('piestat2');

new Chart(ctx1, {
  type: 'pie',
  data: {
    labels: ['RV', 'CV', 'UNSURVEYED'],
    datasets: [{
      label: '# of Votes',
      data: [100, 80, 20],
      borderWidth: 1,
      backgroundColor: [
        'rgb(54, 162, 235)',
        'rgb(255, 99, 132)',
        'rgb(255, 205, 86)'
      ],
      hoverOffset: 4
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

