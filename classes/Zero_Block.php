<?php

/**
 * Класс Zero_Block
 */

class Zero_Block
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $name_def = 'zb';

	/**
	 * @var string
	 */
	protected $tag = 'div';

	/**
	 * @var int
	 */
	static protected $count = 0;

	/**
	 * @var array
	 */
	protected $sections = array(
		'top' => [
			'open'
		],
		'main' => [
			'content'
		],
		'bottom' => [
			'close'
		],
		'footer' => [],
	);


	/**
	 * @param string
	 */
	public function __construct(Zero_Factory_Interface $config)
	{
		if(!$config->has('name')) {
			$name = $this->name_def . ++self::$count;
		} else {
			$name = $config->get('name');
			++self::$count;
		}

		$this->name = $this->name_def . '-' . $name;

		add_action('admin_head', [ $this, 'style' ]);
	}


	/**
	 * @param string
	 */
	public function show($args = array())
	{
		$sections = $this->sections;
		echo $this->render_section($sections['top']);
		echo $this->render_section($sections['main']);
		echo $this->render_section($sections['bottom']);
	}


	/**
	 * @param string
	 */
	public function render_section($section)
	{
		$temp = '';
		foreach ($section as $value) {
			if(method_exists($this, $value)) {
				$temp .= $this->$value();
			}
		}

		return $temp;
	}



	protected function open()
	{
	{
		$tag = $this->tag;
		$class = $this->name_def . ' ' . $this->name;
		return "<$tag class=\"$class\">";
	}
	}

	protected function content()
	{
		return "<b>Имя блока: </b> $this->name";
	}

	protected function close()
	{
		$tag = $this->tag;
		return "</$tag> <!-- // close $this->name -->";
	}



	public function style()
	{
		echo "<style>\n" . $this->css() . "\n</style>";
	}

	protected function css()
	{
		return <<<CSS
.zb {
	padding: 10px;
	border: 1px solid black;
	margin: 10px 20px 0 2px;
	background-color: grey;
	color: white;

	display: flex;
	justify-content: stretch;
	width: auto;

	box-sizing: border-box;
}

.zb-err {
	border: 1px solid #d63638;
	background-color: #d63638;
}

.zb-info {
	border: 1px solid #2271b1;
	background-color: #2271b1;
}
CSS;
	}
}