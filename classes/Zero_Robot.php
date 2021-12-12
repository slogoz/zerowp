<?

/**
 * Zero Robot Wordpress [ZR]
 */
// die('ZERO ROBOT');
class Zero_Robot
{
    static public $notice_type = 'notice';
    private $count_obj = 0;
    private $zero_obj;

    public function add_obj($obj = false)
    {
        $zobj = $this->zobj_def();

        if(is_array($obj)) {
            foreach ($obj as $key => $value) {
                $zobj[$key] = $value;
            }
        }

        $zobj['iid'] = $this->count_obj;
        $this->zero_obj[$this->count_obj] = $zobj;
        $this->zero_obj['type'][$zobj['type']][] = $this->count_obj++;
    }

    protected function zobj_def()
    {
        $zobj = [
            'type' => 'notice',
            'name' => ZR,
            'id' => 'zobj',
            'notice' => "%name%: Привет МИР!\nМой iid:%iid%,\nа id:%id%\n[type:%type%]\n-------------"
        ];

        return $zobj;
    }

    public function notice()
    {
        $type = $this->notice_type;
        $notice = '#ZR Инициализация Zero Robot Wordpress' . PHP_EOL;

        $arr_notices = '';
        if(array_key_exists($type, $this->zero_obj['type'])) {
            foreach ($this->zero_obj['type'][$type] as $iid) {
                $notice.= $this->render_notice($iid) . PHP_EOL;
            }
        }

        printf(
            '<p class="zerowp_notice"><pre>%s</pre></p>',
            $notice
        );
    }

    protected function render_notice($iid)
    {
        $zobj = $this->zero_obj[$iid];
        $notice = $this->get_notice($zobj);
        preg_match_all('/\%(.*?)\%/', $notice, $temps_arr);
        foreach ($temps_arr[1] as $temp) {
            $value = "#$temp#";
            if(array_key_exists($temp, $zobj)) {
                if(is_string($zobj[$temp]) || is_numeric($zobj[$temp]) || is_bool($zobj[$temp])) {
                    $value = $zobj[$temp];
                } else {
                    $value = "#$temp!str|num|bool#";
                }
            }
            $notice = preg_replace("/\%$temp\%/", $value, $notice);
        }
        return $notice;
    }

    protected function get_notice($zobj)
    {
        if(array_key_exists('notice', $zobj)) {
            return $zobj['notice'];
        }
        return ZR . ": Объект iid:{$iid} не имеет notice!";
    }
}
