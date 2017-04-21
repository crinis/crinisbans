<?php
namespace crinis\cb\Model\DB;

abstract class Flags_DB extends Post_DB {
	protected function get_table_name() {
		return 'flags';
	}

	protected function add_columns() {
		parent::add_columns();
		$this->columns['immunity']  = [
			'type' => 'int NOT NULL DEFAULT 0',
			'format' => '%d',
		];
		$this->columns = array_merge( $this->columns,$this->get_flags() );
	}


	protected function get_game_flags() {
		$columns = [];
		$flag_meta = [
			'type' => 'bool NOT NULL DEFAULT 0',
			'format' => '%d',
		];
		$columns['flag_z']  = $flag_meta;
		$columns['flag_a']  = $flag_meta;
		$columns['flag_b']  = $flag_meta;
		$columns['flag_c']  = $flag_meta;
		$columns['flag_d']  = $flag_meta;
		$columns['flag_e']  = $flag_meta;
		$columns['flag_f']  = $flag_meta;
		$columns['flag_g']  = $flag_meta;
		$columns['flag_h']  = $flag_meta;
		$columns['flag_i']  = $flag_meta;
		$columns['flag_j']  = $flag_meta;
		$columns['flag_k']  = $flag_meta;
		$columns['flag_l']  = $flag_meta;
		$columns['flag_m']  = $flag_meta;
		$columns['flag_n']  = $flag_meta;
		$columns['flag_o']  = $flag_meta;
		$columns['flag_p']  = $flag_meta;
		$columns['flag_q']  = $flag_meta;
		$columns['flag_r']  = $flag_meta;
		$columns['flag_s']  = $flag_meta;
		$columns['flag_t']  = $flag_meta;
		return $columns;
	}

	public function get_flags() {
		return $this->get_game_flags();
	}
}
