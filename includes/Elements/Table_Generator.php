<?php
namespace Essential_Addons_Elementor\Elements;

if (!defined('ABSPATH')) { exit; }

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class Table_Generator extends Widget_Base {
	use \Essential_Addons_Elementor\Traits\Helper;

	protected $eaelRElem = 1;

	public function get_name() {
		return 'eael-table-generator';
	}

	public function get_title() {
		return esc_html__( 'EA Table', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-table';
	}

    public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'eaeldt_general_section',
			[
				'label' => __( 'General', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'eaeldt_table_source',
			[
				'label'     => __( 'Source', 'essential-addons-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'options'	=> [
					''   		=> __( 'Select One', 'essential-addons-elementor' ),
					'csv'   	=> __( 'CSV File', 'essential-addons-elementor' ),
					'ninja'     => __( 'Ninja Table', 'essential-addons-elementor' ),
					'datatable'	=> __( 'WP DataTable', 'essential-addons-elementor' ),
				],
				'default'	=> ''
            ]
		);

		$this->add_control(
			'eaeldt_csv_file',
			[
				'label' => __( 'Upload a CSV File', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'file',
				'condition' => [
					'eaelsv_table_source' => 'csv',
				],
			]
		);

		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings_for_display();
		//$iconNew = $settings['eaelsv_icon_new'];
		$eaeldtCsvSrc = $settings['eaeldt_csv_file']['url'];
		$eaeldtCsvFile = file($eaeldtCsvSrc);
		$eaeldtCsvData = [];
		foreach ($eaeldtCsvFile as $line) {
			$eaeldtCsvData[] = str_getcsv($line);
		}
		echo "<pre>";
		print_r($eaeldtCsvData);
		//echo count($eaeldtCsvData);
		?>
		<div style="width:100%; border:1px solid #000; display:block;">
		<table width="100%" cellpadding="0" cellspacing="0" border="1">
			<thead>
				<tr>
					<?php for($th=0; $th < count($eaeldtCsvData[0]); $th++): ?>
						<?php if($eaeldtCsvData[0][$th]!='') { ?>
							<th><?php echo $eaeldtCsvData[0][$th]; ?></th>
						<?php } ?>
					<?php endfor; ?>
				</tr>
			</thead>
			<tbody>
				<?php for($tbr=1; $tbr < count($eaeldtCsvData); $tbr++): ?>
				<tr>
					<td style="width:25%;">
						<?php echo $eaeldtCsvData[$tbr][0]; ?>
					</td>
					<td style="width:25%;">
						<?php echo $eaeldtCsvData[$tbr][1]; ?>
					</td>
					<td style="width:25%;">
						<?php echo $eaeldtCsvData[$tbr][2]; ?>
					</td>
					<td style="width:25%;">
						<?php echo $eaeldtCsvData[$tbr][3]; ?>
					</td>
				</tr>
				<?php endfor; ?>
			</tbody>
		</table>
		</div>
		<?php
	}

}