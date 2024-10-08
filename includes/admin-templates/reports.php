<?php
/**
 * Admin Template: Reports
 *
 * @package includes/admin-templates/reports
 * @author Liquidpoll
 */

if ( empty( $poll_id = isset( $_GET['poll-id'] ) ? sanitize_text_field( wp_unslash( $_GET['poll-id'] ) ) : '' ) ) {
	liquidpoll()->print_notice( esc_html__( 'Please select a poll.', 'wp-poll' ), 'error', false );
}

?>


<div class="liquidpoll-reports-field">
    <label for="liquidpoll-poll-selection"><?php esc_html_e( 'Select Poll', 'wp-poll' ); ?></label>
    <select name="poll-id" id="liquidpoll-poll-selection" data-url="<?php echo esc_url( admin_url( 'edit.php?post_type=poll&page=settings' ) ); ?>">
        <option value=""><?php esc_html_e( 'Choose a Poll', 'wp-poll' ); ?></option>
		<?php foreach ( get_posts( 'post_type=poll&posts_per_page=-1&fields=ids' ) as $__p_id ) : ?>
            <option value="<?php echo esc_attr( $__p_id ); ?>" <?php selected( $poll_id, $__p_id ); ?>><?php echo esc_html( get_the_title( $__p_id ) ); ?></option>
		<?php endforeach; ?>
    </select>
</div>


<?php
if ( empty( $poll_id ) ) {
	return;
}

$poll         = liquidpoll_get_poll( $poll_id );
$seriesVotes  = array_values( $poll->get_poll_reports( 'counts' ) );
$seriesLabels = array_values( $poll->get_poll_reports( 'labels' ) );
$totalVotes   = $poll->get_poll_reports( 'total_votes' );
$chart_type   = isset( $_GET['type'] ) ? sanitize_text_field( wp_unslash( $_GET['type'] ) ) : 'pie';

if ( $chart_type == 'pie' ) {
	$series = array_values( $poll->get_poll_reports( 'counts' ) );
	$series = wp_json_encode( $series );
} else if ( $chart_type == 'bar' ) {
	$series = array(
		'name' => esc_html__( 'Voted', 'wp-poll' ),
		'data' => array_values( $poll->get_poll_reports( 'percentages' ) ),
	);
	$series = sprintf( '[%s]', preg_replace( '/"([a-zA-Z]+[a-zA-Z0-9_]*)":/', '$1:', wp_json_encode( $series ) ) );
}
?>


<div id="liquidpoll-chart-report"></div>

<script>
    let pollTitle = '<?php printf( esc_html__( 'Poll : %s', 'wp-poll' ), $poll->get_name() ); ?>',
        series = <?php echo $series; ?>,
        seriesVotes = <?php echo wp_json_encode( $seriesVotes ); ?>,
        seriesLabels = <?php echo wp_json_encode( $seriesLabels ); ?>,
        totalVotes = <?php echo esc_html( $totalVotes ); ?>,
        chartType = '<?php echo esc_html( $chart_type ); ?>',
        options = {
            chart: {
                height: 360,
                type: chartType,
            },
            labels: seriesLabels,
            series: series,
            plotOptions: {
                bar: {
                    dataLabels: {
                        position: 'top', // top, center, bottom
                    },
                }
            },
            dataLabels: {
                enabled: chartType !== 'pie',
                formatter: function (val, opts) {
                    return 'Voted ' + seriesVotes[opts.dataPointIndex] + '/' + totalVotes;
                },
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
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
                        if (chartType === 'bar') {
                            return val + '%';
                        }
                        return val;
                    }
                }

            },
            title: {
                text: pollTitle,
                floating: false,
                offsetY: 330,
                align: 'left',
                style: {
                    color: '#444',
                    margin: '20px',
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        },
        chart = new ApexCharts(
            document.querySelector("#liquidpoll-chart-report"),
            options
        );

    chart.render();
</script>
