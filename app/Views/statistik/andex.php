<div class="inputan col-md-6 bg-white ">
            <div class="tabel">
                <?php foreach ($nMax->getResult() as $row) {;}?>
                <?php foreach ($nMin->getResult() as $nmin) {;}?>
                <?php foreach ($nAvg->getResult() as $ntr) {;}?>

                <canvas id="myChart" width="10" height="10">
                </canvas>

                <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Nilai Tertinggi', 'Nilai Terendah', 'Nilai Rata-Rata'],
                        datasets: [{
                            axis: 'y',
                            label: '',
                            data: [<?php echo $row->nilai ?>, <?php echo $nmin->nilai ?>,
                                <?php echo $ntr->nilai ?>
                            ],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                </script>
            </div>
        </div>