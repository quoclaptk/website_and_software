<?php namespace Modules\Helpers;

use Modules\PhalconVn\WordTranslateService;

class FormHelper
{
    public function __construct()
    {
        $wordService = new WordTranslateService();
        $this->word = $wordService->getWordTranslation();
    }

    public function carTypeSelect()
    {
        $data = [];
        if ($this->word['_loai_xe_4_cho_ngoi'] != '') {
            $data[$this->word['_loai_xe_4_cho_ngoi']] = $this->word['_loai_xe_4_cho_ngoi'];
        }

        if ($this->word['_loai_xe_7_cho_ngoi'] != '') {
            $data[$this->word['_loai_xe_7_cho_ngoi']] = $this->word['_loai_xe_7_cho_ngoi'];
        }

        if ($this->word['_loai_xe_16_cho_ngoi'] != '') {
            $data[$this->word['_loai_xe_16_cho_ngoi']] = $this->word['_loai_xe_16_cho_ngoi'];
        }

        return $data;
    }

    public function classSelect($options = null)
    {
        $data = [];
        if ($options == null) {
            $data[''] = $this->word['_chon_lop'];
        }
        
        if ($this->word['_lop_1'] != '') {
            $data[$this->word['_lop_1']] = $this->word['_lop_1'];
        }

        if ($this->word['_lop_2'] != '') {
            $data[$this->word['_lop_2']] = $this->word['_lop_2'];
        }

        if ($this->word['_lop_3'] != '') {
            $data[$this->word['_lop_3']] = $this->word['_lop_3'];
        }

        if ($this->word['_lop_4'] != '') {
            $data[$this->word['_lop_4']] = $this->word['_lop_4'];
        }

        if ($this->word['_lop_5'] != '') {
            $data[$this->word['_lop_5']] = $this->word['_lop_5'];
        }

        if ($this->word['_lop_6'] != '') {
            $data[$this->word['_lop_6']] = $this->word['_lop_6'];
        }

        if ($this->word['_lop_7'] != '') {
            $data[$this->word['_lop_7']] = $this->word['_lop_7'];
        }

        if ($this->word['_lop_8'] != '') {
            $data[$this->word['_lop_8']] = $this->word['_lop_8'];
        }

        if ($this->word['_lop_9'] != '') {
            $data[$this->word['_lop_9']] = $this->word['_lop_9'];
        }

        if ($this->word['_lop_10'] != '') {
            $data[$this->word['_lop_10']] = $this->word['_lop_10'];
        }

        if ($this->word['_lop_11'] != '') {
            $data[$this->word['_lop_11']] = $this->word['_lop_11'];
        }

        if ($this->word['_lop_12'] != '') {
            $data[$this->word['_lop_12']] = $this->word['_lop_12'];
        }

        if ($this->word['_on_dai_hoc'] != '') {
            $data[$this->word['_on_dai_hoc']] = $this->word['_on_dai_hoc'];
        }

        if ($this->word['_lop_nang_khieu'] != '') {
            $data[$this->word['_lop_nang_khieu']] = $this->word['_lop_nang_khieu'];
        }

        if ($this->word['_lop_ngoai_ngu'] != '') {
            $data[$this->word['_lop_ngoai_ngu']] = $this->word['_lop_ngoai_ngu'];
        }

        if ($this->word['_lop_khac'] != '') {
            $data[$this->word['_lop_khac']] = $this->word['_lop_khac'];
        }

        if ($this->word['_lop_la'] != '') {
            $data[$this->word['_lop_la']] = $this->word['_lop_la'];
        }

        if ($this->word['_he_dai_hoc'] != '') {
            $data[$this->word['_he_dai_hoc']] = $this->word['_he_dai_hoc'];
        }

        return $data;
    }

    public function caseClassSelect()
    {
        $data = [];
        $data[''] = $this->word['_chon_so_buoi'];

        if ($this->word['_1_buoi'] != '') {
            $data[$this->word['_1_buoi']] = $this->word['_1_buoi'];
        }

        if ($this->word['_2_buoi'] != '') {
            $data[$this->word['_2_buoi']] = $this->word['_2_buoi'];
        }

        if ($this->word['_3_buoi'] != '') {
            $data[$this->word['_3_buoi']] = $this->word['_3_buoi'];
        }

        if ($this->word['_4_buoi'] != '') {
            $data[$this->word['_4_buoi']] = $this->word['_4_buoi'];
        }

        if ($this->word['_5_buoi'] != '') {
            $data[$this->word['_5_buoi']] = $this->word['_5_buoi'];
        }

        if ($this->word['_6_buoi'] != '') {
            $data[$this->word['_6_buoi']] = $this->word['_6_buoi'];
        }

        if ($this->word['_7_buoi'] != '') {
            $data[$this->word['_7_buoi']] = $this->word['_7_buoi'];
        }

        return $data;
    }

    public function requestLevelSelect()
    {
        $data = [];
        $data[''] = $this->word['_chon_yeu_cau'];

        if ($this->word['_sinh_vien'] != '') {
            $data[$this->word['_sinh_vien']] = $this->word['_sinh_vien'];
        }

        if ($this->word['_sinh_vien_nam'] != '') {
            $data[$this->word['_sinh_vien_nam']] = $this->word['_sinh_vien_nam'];
        }

        if ($this->word['_sinh_vien_nu'] != '') {
            $data[$this->word['_sinh_vien_nu']] = $this->word['_sinh_vien_nu'];
        }

        if ($this->word['_giao_vien'] != '') {
            $data[$this->word['_giao_vien']] = $this->word['_giao_vien'];
        }

        if ($this->word['_giao_vien_nam'] != '') {
            $data[$this->word['_giao_vien_nam']] = $this->word['_giao_vien_nam'];
        }

        if ($this->word['_giao_vien_nu'] != '') {
            $data[$this->word['_giao_vien_nu']] = $this->word['_giao_vien_nu'];
        }

        if ($this->word['_cu_nhan'] != '') {
            $data[$this->word['_cu_nhan']] = $this->word['_cu_nhan'];
        }

        if ($this->word['_cu_nhan_nam'] != '') {
            $data[$this->word['_cu_nhan_nam']] = $this->word['_cu_nhan_nam'];
        }

        if ($this->word['_cu_nhan_nu'] != '') {
            $data[$this->word['_cu_nhan_nu']] = $this->word['_cu_nhan_nu'];
        }

        if ($this->word['_thac_sy'] != '') {
            $data[$this->word['_thac_sy']] = $this->word['_thac_sy'];
        }

        if ($this->word['_thac_sy_nam'] != '') {
            $data[$this->word['_thac_sy_nam']] = $this->word['_thac_sy_nam'];
        }

        if ($this->word['_thac_sy_nu'] != '') {
            $data[$this->word['_thac_sy_nu']] = $this->word['_thac_sy_nu'];
        }

        if ($this->word['_tien_sy'] != '') {
            $data[$this->word['_thac_sy']] = $this->word['_thac_sy'];
        }

        if ($this->word['_tien_sy_nam'] != '') {
            $data[$this->word['_tien_sy_nam']] = $this->word['_tien_sy_nam'];
        }

        if ($this->word['_tien_sy_nu'] != '') {
            $data[$this->word['_tien_sy_nu']] = $this->word['_tien_sy_nu'];
        }

        return $data;
    }

    public function voiceSelect()
    {
        $data = [];
        $data[''] = $this->word['_chon_giong_noi'];

        if ($this->word['_mien_bac'] != '') {
            $data[$this->word['_mien_bac']] = $this->word['_mien_bac'];
        }

        if ($this->word['_mien_trung'] != '') {
            $data[$this->word['_mien_trung']] = $this->word['_mien_trung'];
        }

        if ($this->word['_mien_nam'] != '') {
            $data[$this->word['_mien_nam']] = $this->word['_mien_nam'];
        }

        return $data;
    }

    public function levelSelect()
    {
        $data = [];
        $data[''] = $this->word['_trinh_do'];

        if ($this->word['_cu_nhan_su_pham'] != '') {
            $data[$this->word['_cu_nhan_su_pham']] = $this->word['_cu_nhan_su_pham'];
        }

        if ($this->word['_sinh_vien_su_pham'] != '') {
            $data[$this->word['_sinh_vien_su_pham']] = $this->word['_sinh_vien_su_pham'];
        }

        if ($this->word['_giao_vien'] != '') {
            $data[$this->word['_giao_vien']] = $this->word['_giao_vien'];
        }

        if ($this->word['_sinh_vien'] != '') {
            $data[$this->word['_sinh_vien']] = $this->word['_sinh_vien'];
        }

        if ($this->word['_cu_nhan'] != '') {
            $data[$this->word['_cu_nhan']] = $this->word['_cu_nhan'];
        }

        if ($this->word['_thac_sy'] != '') {
            $data[$this->word['_thac_sy']] = $this->word['_thac_sy'];
        }

        if ($this->word['_tien_sy'] != '') {
            $data[$this->word['_tien_sy']] = $this->word['_tien_sy'];
        }

        if ($this->word['_ky_su'] != '') {
            $data[$this->word['_ky_su']] = $this->word['_ky_su'];
        }

        if ($this->word['_bang_khac'] != '') {
            $data[$this->word['_bang_khac']] = $this->word['_bang_khac'];
        }

        return $data;
    }

    public function genderSelect()
    {
        $data = [];
        $data[''] = $this->word['_gioi_tinh'];

        if ($this->word['_nam'] != '') {
            $data[$this->word['_nam']] = $this->word['_nam'];
        }

        if ($this->word['_nu'] != '') {
            $data[$this->word['_nu']] = $this->word['_nu'];
        }

        return $data;
    }

    public function subjectsTrainingCheckbox()
    {
        $data = [];

        if ($this->word['_toan'] != '') {
            $data[$this->word['_toan']] = $this->word['_toan'];
        }

        if ($this->word['_ly'] != '') {
            $data[$this->word['_ly']] = $this->word['_ly'];
        }

        if ($this->word['_hoa'] != '') {
            $data[$this->word['_hoa']] = $this->word['_hoa'];
        }

        if ($this->word['_van'] != '') {
            $data[$this->word['_van']] = $this->word['_van'];
        }

        if ($this->word['_tieng_anh'] != '') {
            $data[$this->word['_tieng_anh']] = $this->word['_tieng_anh'];
        }

        if ($this->word['_sinh'] != '') {
            $data[$this->word['_sinh']] = $this->word['_sinh'];
        }

        if ($this->word['_bao_bai'] != '') {
            $data[$this->word['_bao_bai']] = $this->word['_bao_bai'];
        }

        if ($this->word['_su'] != '') {
            $data[$this->word['_su']] = $this->word['_su'];
        }

        if ($this->word['_tieng_viet'] != '') {
            $data[$this->word['_tieng_viet']] = $this->word['_tieng_viet'];
        }

        if ($this->word['_dia'] != '') {
            $data[$this->word['_dia']] = $this->word['_dia'];
        }

        if ($this->word['_ve'] != '') {
            $data[$this->word['_ve']] = $this->word['_ve'];
        }

        if ($this->word['_dan_nhac'] != '') {
            $data[$this->word['_dan_nhac']] = $this->word['_dan_nhac'];
        }

        if ($this->word['_tin_hoc'] != '') {
            $data[$this->word['_tin_hoc']] = $this->word['_tin_hoc'];
        }

        if ($this->word['_luyen_chu_dep'] != '') {
            $data[$this->word['_luyen_chu_dep']] = $this->word['_luyen_chu_dep'];
        }

        if ($this->word['_tieng_trung'] != '') {
            $data[$this->word['_tieng_trung']] = $this->word['_tieng_trung'];
        }

        if ($this->word['_tieng_nhat'] != '') {
            $data[$this->word['_tieng_nhat']] = $this->word['_tieng_nhat'];
        }

        if ($this->word['_anh_van_giao_tiep'] != '') {
            $data[$this->word['_anh_van_giao_tiep']] = $this->word['_anh_van_giao_tiep'];
        }

        if ($this->word['_tieng_han'] != '') {
            $data[$this->word['_tieng_han']] = $this->word['_tieng_han'];
        }

        if ($this->word['_ke_toan'] != '') {
            $data[$this->word['_ke_toan']] = $this->word['_ke_toan'];
        }

        if ($this->word['_tieng_nga'] != '') {
            $data[$this->word['_tieng_nga']] = $this->word['_tieng_nga'];
        }

        if ($this->word['_tieng_phap'] != '') {
            $data[$this->word['_tieng_phap']] = $this->word['_tieng_phap'];
        }

        if ($this->word['_tieng_duc'] != '') {
            $data[$this->word['_tieng_duc']] = $this->word['_tieng_duc'];
        }

        if ($this->word['_tieng_campuchia'] != '') {
            $data[$this->word['_tieng_campuchia']] = $this->word['_tieng_campuchia'];
        }

        if ($this->word['_tieng_thai'] != '') {
            $data[$this->word['_tieng_thai']] = $this->word['_tieng_thai'];
        }

        if ($this->word['_tieng_y'] != '') {
            $data[$this->word['_tieng_y']] = $this->word['_tieng_y'];
        }

        if ($this->word['_mon_khac'] != '') {
            $data[$this->word['_mon_khac']] = $this->word['_mon_khac'];
        }

        return $data;
    }

    public function trainingTimeCheckBox()
    {
        $data = [];

        if ($this->word['_thu_2_b_sang'] != '') {
            $data[$this->word['_thu_2_b_sang']] = $this->word['_thu_2_b_sang'];
        }

        if ($this->word['_thu_2_b_chieu'] != '') {
            $data[$this->word['_thu_2_b_chieu']] = $this->word['_thu_2_b_chieu'];
        }

        if ($this->word['_thu_2_b_toi'] != '') {
            $data[$this->word['_thu_2_b_toi']] = $this->word['_thu_2_b_toi'];
        }

        if ($this->word['_thu_3_b_sang'] != '') {
            $data[$this->word['_thu_3_b_sang']] = $this->word['_thu_3_b_sang'];
        }

        if ($this->word['_thu_3_b_chieu'] != '') {
            $data[$this->word['_thu_3_b_chieu']] = $this->word['_thu_3_b_chieu'];
        }

        if ($this->word['_thu_3_b_toi'] != '') {
            $data[$this->word['_thu_3_b_toi']] = $this->word['_thu_3_b_toi'];
        }

        if ($this->word['_thu_4_b_sang'] != '') {
            $data[$this->word['_thu_4_b_sang']] = $this->word['_thu_4_b_sang'];
        }

        if ($this->word['_thu_4_b_chieu'] != '') {
            $data[$this->word['_thu_4_b_chieu']] = $this->word['_thu_4_b_chieu'];
        }

        if ($this->word['_thu_4_b_toi'] != '') {
            $data[$this->word['_thu_4_b_toi']] = $this->word['_thu_4_b_toi'];
        }

        if ($this->word['_thu_5_b_sang'] != '') {
            $data[$this->word['_thu_5_b_sang']] = $this->word['_thu_5_b_sang'];
        }

        if ($this->word['_thu_5_b_chieu'] != '') {
            $data[$this->word['_thu_5_b_chieu']] = $this->word['_thu_5_b_chieu'];
        }

        if ($this->word['_thu_5_b_toi'] != '') {
            $data[$this->word['_thu_5_b_toi']] = $this->word['_thu_5_b_toi'];
        }

        if ($this->word['_thu_6_b_sang'] != '') {
            $data[$this->word['_thu_6_b_sang']] = $this->word['_thu_6_b_sang'];
        }

        if ($this->word['_thu_6_b_chieu'] != '') {
            $data[$this->word['_thu_6_b_chieu']] = $this->word['_thu_6_b_chieu'];
        }

        if ($this->word['_thu_6_b_toi'] != '') {
            $data[$this->word['_thu_6_b_toi']] = $this->word['_thu_6_b_toi'];
        }

        if ($this->word['_thu_7_b_sang'] != '') {
            $data[$this->word['_thu_7_b_sang']] = $this->word['_thu_7_b_sang'];
        }

        if ($this->word['_thu_7_b_chieu'] != '') {
            $data[$this->word['_thu_7_b_chieu']] = $this->word['_thu_7_b_chieu'];
        }

        if ($this->word['_thu_7_b_toi'] != '') {
            $data[$this->word['_thu_7_b_toi']] = $this->word['_thu_7_b_toi'];
        }

        if ($this->word['_cn_b_sang'] != '') {
            $data[$this->word['_cn_b_sang']] = $this->word['_cn_b_sang'];
        }

        if ($this->word['_cn_b_chieu'] != '') {
            $data[$this->word['_cn_b_chieu']] = $this->word['_cn_b_chieu'];
        }

        if ($this->word['_cn_b_toi'] != '') {
            $data[$this->word['_cn_b_toi']] = $this->word['_cn_b_toi'];
        }

        return $data;
    }

    public function methodSelect()
    {
        $data = [];
        $data[''] = $this->word['_chon_hinh_thuc_nhan_lop'];

        if ($this->word['_hinh_thuc_nhan_lop_1'] != '') {
            $data[$this->word['_hinh_thuc_nhan_lop_1']] = $this->word['_hinh_thuc_nhan_lop_1'];
        }

        if ($this->word['_hinh_thuc_nhan_lop_2'] != '') {
            $data[$this->word['_hinh_thuc_nhan_lop_2']] = $this->word['_hinh_thuc_nhan_lop_2'];
        }

        return $data;
    }

    public function boxOptionSelect()
    {
        $data = [];
        $data[''] = $this->word['_box_tuy_chon'];

        for ($i = 1; $i <= 15; $i++) {
            if ($this->word['_lua_chon_' . $i] != '') {
                $data[$this->word['_lua_chon_' . $i]] = $this->word['_lua_chon_' . $i];
            }
        }

        return $data;
    }

    public function hourSelect($step = 1)
    {
        $data = [];
        for ($i = 0; $i < 24; $i+=$step) {
            $label = ($i < 10) ? '0' . $i : $i;
            $data[] = 'Lúc ' . $label . 'h';
        }

        $data = array_combine($data, $data);

        return $data;
    }

    public function hourSelectOne($start = 1, $end = 24, $step = 1)
    {
        $data = [];
        for ($i = $start; $i <= $end; $i+=$step) {
            $data[] = $i . ' ' . $this->word['_gio'];
        }

        if ($this->word['_luc_khac'] != '') {
            $data[] = $this->word['_luc_khac'];
        }

        $data = array_combine($data, $data);

        return $data;
    }

    public function minuteSelect($step = 5)
    {
        $data = [];
        for ($i = 0; $i < 60; $i+=$step) {
            $data[] = (($i < 10) ? '0' . $i : $i) . 'phút';
        }

        $data = array_combine($data, $data);

        return $data;
    }

    public function languagesGoogleTranslate()
    {
        $languages = [
            'vi' => 'Việt Nam',
            'en' => 'Anh',
            'de' => 'Đức',
            'ko' => 'Hàn quốc',
            'ru' => 'Nga',
            'zh-CN' => 'Trung quốc',
            'it' => 'Italia',
        ];

        return $languages;
    }

    public function proviceSelect($options = null)
    {
        $dataProvinces = $this->getProviceFileData($options);
        $data = [];
        $data[''] = $this->word['_chon_tinh_thanh'];
        foreach ($dataProvinces as $dataProvince) {
            $data[$dataProvince['name']] = $dataProvince['name'];
        }

        return $data;
    }

    public function getProviceFileData($options = null)
    {
        if (file_exists('data/province.json')) {
            $provinceData = file_get_contents('data/province.json');
            $provinceData = json_decode($provinceData);

            $proVinceNames = [];
            foreach ($provinceData as $province) {
                $item['code'] = $province->code;
                $item['name'] = $province->name;
                $proVinceNames[] = $item;
            }

            //sort alphalet abc
            usort($proVinceNames, function ($a, $b) {
                return strcmp($a["name"], $b["name"]);
            });

            if (isset($options['special']) && $options['special'] == true) {
                $specialProvinces = [];
                foreach ($proVinceNames as $key => $proVinceName) {
                    if ($proVinceName['code'] == 79) {
                        $specialProvinces[0] = $proVinceName;
                        unset($proVinceNames[$key]);
                    }

                    if ($proVinceName['code'] == 01) {
                        $specialProvinces[1] = $proVinceName;
                        unset($proVinceNames[$key]);
                    }

                    if ($proVinceName['code'] == 48) {
                        $specialProvinces[2] = $proVinceName;
                        unset($proVinceNames[$key]);
                    }
                }

                $proVinceNames = array_values($proVinceNames);
                ksort($specialProvinces);

                return array_merge($specialProvinces, $proVinceNames);
            }

            return $proVinceNames;
        } else {
            return false;
        }
    }
}
