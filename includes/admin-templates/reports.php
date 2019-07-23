<?php
/**
 * Admin Template: Reports
 *
 * @package includes/admin-templates/reports
 * @author Pluginbazar
 */


$poll_id = isset( $_GET['poll-id'] ) ? sanitize_text_field( $_GET['poll-id'] ) : '';

if ( empty( $poll_id ) ) {
	return;
}

$poll         = wpp_get_poll( $poll_id );
$poll_results = $poll->get_poll_results();
$totalVotes   = isset( $poll_results['total'] ) ? $poll_results['total'] : 0;
$singles      = isset( $poll_results['singles'] ) && is_array( $poll_results['singles'] ) ? $poll_results['singles'] : array();
$seriesVotes  = array_values( $singles );
$percentages  = isset( $poll_results['percentages'] ) && is_array( $poll_results['percentages'] ) ? $poll_results['percentages'] : array();
$seriesValues = array_values( $percentages );
$seriesLabels = array();

foreach ( $percentages as $option_id => $percentage ) {
	$seriesLabels[] = $poll->get_option_label( $option_id );
}

?>

<div id="wpp-chart-report"></div>


<script>

    let pollTitle = '<?php printf( esc_html__( 'Poll : %s', 'wp-poll' ), $poll->get_name() ); ?>',
        seriesName = '<?php esc_html_e( 'Voted', 'wp-poll' ); ?>',
        seriesVotes = <?php echo json_encode( $seriesVotes ); ?>,
        seriesValues = <?php echo json_encode( $seriesValues ); ?>,
        seriesLabels = <?php echo json_encode( $seriesLabels ); ?>,
        totalVotes = <?php echo esc_html( $totalVotes ); ?>,
        options = {
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return 'Voted ' + seriesVotes[opts.dataPointIndex] + '/' + totalVotes;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            series: [{
                name: seriesName,
                data: seriesValues,
            }],
            xaxis: {
                categories: seriesLabels,
                position: 'top',
                labels: {
                    offsetY: -18,

                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    offsetY: -35,

                }
            },
            fill: {
                gradient: {
                    shade: 'light',
                    type: "horizontal",
                    shadeIntensity: 0.25,
                    gradientToColors: undefined,
                    inverseColors: true,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [50, 0, 100, 100]
                },
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    formatter: function (val) {
                        return val + "%";
                    }
                }

            },
            title: {
                text: pollTitle,
                floating: true,
                offsetY: 320,
                align: 'center',
                style: {
                    color: '#444'
                }
            },
        },
        chart = new ApexCharts(
            document.querySelector("#wpp-chart-report"),
            options
        );

    chart.render();
</script>
