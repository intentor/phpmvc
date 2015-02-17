<?php
	/**
	 * Pager model.
	 */
	class PagerModel {
		/** Current page. */
		public $current_page;
		/** Total pages. */
		public $total_pages;
	
		/**
		 * Checks if it's the first page.
		 * @return boolean
		 */
		public function is_first() {
			return $this->current_page == 1;
		}
	
		/**
		 * Checks if it's the last page.
		 * @return boolean
		 */
		public function is_last() {
			return $this->current_page == $this->total_pages;
		}
	}