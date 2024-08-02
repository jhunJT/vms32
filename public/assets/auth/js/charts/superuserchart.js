const ctx = document.getElementById('piestat1');

var totalRVR = ((totalRV - totalCV) / totalRV) * 100;
var totalCVR = (totalCV / totalRV) * 100;
var percentMA = ((totalMA/totalCV) * 100).toFixed(2);
var percentCV = ((totalMA/totalCV) * 100).toFixed(2);
var roundedRV = totalRVR.toFixed(2);
var roundedCV = totalCVR.toFixed(2);

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['UNSURVEYED', 'CV' ],
    datasets: [{
      label: '# of Votes',
      data: [roundedRV, roundedCV], // Use numbers only
      borderWidth: 4,
      backgroundColor: [ "#0f9cf3" , "#f32f53" ],
      hoverBackgroundColor: ["#58baf7", "#FF8282"],
      hoverBorderColor: "#fff"
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true,
      }
    }
  }
});


const ctx1 = document.getElementById('piestat2');

new Chart(ctx1, {
  type: 'doughnut',
  data: {
    labels: ['MANUAL', 'CV'],
    datasets: [{
      label: '# of Votes',
      data: [totalMA, totalCV],
      borderWidth: 1,
      backgroundColor: [
        'rgb(106,168,79)',
        'rgb(255, 99, 132)',
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

