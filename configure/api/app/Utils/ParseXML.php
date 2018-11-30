<?php
namespace App\Utils;

class ParseXML
{

    private $ary_output = [];

    private $res_parser;

    private $str_xml_data;

    /**
     * xmlを配列に変換する関数である
     *
     * @param string $str_input_xml
     * @return array
     */
    public function parse($str_input_xml)
    {
        $this->res_parser = xml_parser_create();
        xml_parser_set_option($this->res_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_object($this->res_parser, $this);
        xml_set_element_handler($this->res_parser, "tagOpen", "tagClosed");

        xml_set_character_data_handler($this->res_parser, "tagData");

        $this->str_xml_data = xml_parse($this->res_parser, $str_input_xml);
        xml_parser_free($this->res_parser);

        return $this->ary_output;
    }

    /**
     * xmlタグの属性を初期化する。
     *
     * @param
     *            $parser
     * @param string $name
     * @param string $attrs
     */
    public function tagOpen($parser, $name, $attrs)
    {
        $ary_tag = [
            "nodename" => $name,
            "attributes" => $attrs
        ];
        array_push($this->ary_output, $ary_tag);
    }

    /**
     * xmlからデータ取得する関数である。
     *
     * @param object $parser
     * @param object $tagData
     */
    public function tagData($parser, $tag_data)
    {
        if (trim($tag_data)) {
            if (isset($this->ary_output[count($this->ary_output) - 1]['nodevalue'])) {
                $this->ary_output[count($this->ary_output) - 1]['nodevalue'] .= $tag_data;
            } else {
                $this->ary_output[count($this->ary_output) - 1]['nodevalue'] = $tag_data;
            }
        }
    }

    /**
     * xmlを読み込みきったあとで呼び出す関数である
     *
     * @param object $parser
     * @param string $name
     */
    public function tagClosed($parser, $name)
    {
        $this->ary_output[count($this->ary_output) - 2]['childrens'][] =
            $this->ary_output[count($this->ary_output) - 1];

        array_pop($this->ary_output);
    }

    /**
     * 取得したXMLパース
     *
     * @param Array $xml_array
     *            XMLArray
     * @return Object $xml
     */
    public function parseKeyXML($xml_array)
    {
        $xml = new \stdClass();
        // XMLパース
        foreach ($xml_array[0]['childrens'] as $value) {
            if ($value['nodename'] == 'api_user_id') {
                if (! empty($value['nodevalue'])) {
                    $xml->api_user_id = $value['nodevalue'];
                }
            }
            if ($value['nodename'] == 'mail') {
                if (! empty($value['nodevalue'])) {
                    $xml->mail = $value['nodevalue'];
                }
            }
            if ($value['nodename'] == 'url') {
                if (! empty($value['nodevalue'])) {
                    $xml->url = $value['nodevalue'];
                }
            }
            if ($value['nodename'] == 'time') {
                if (! empty($value['nodevalue'])) {
                    $xml->time = $value['nodevalue'];
                }
            }
        }

        return $xml;
    }
}
