const provincesData = [{
    "code": "110000",
    "index": "B",
    "fullname": "北京市",
}, {
    "code": "120000",
    "index": "T",
    "fullname": "天津市",
}, {
    "code": "130000",
    "index": "H",
    "fullname": "河北省",
}, {
    "code": "140000",
    "index": "S",
    "fullname": "山西省",
}, {
    "code": "150000",
    "index": "N",
    "fullname": "内蒙古自治区",
}, {
    "code": "210000",
    "index": "N",
    "fullname": "辽宁省",
}, {
    "code": "220000",
    "index": "J",
    "fullname": "吉林省",
}, {
    "code": "230000",
    "index": "H",
    "fullname": "黑龙江省",
}, {
    "code": "310000",
    "index": "S",
    "fullname": "上海市",
}, {
    "code": "320000",
    "index": "J",
    "fullname": "江苏省",
}, {
    "code": "330000",
    "index": "Z",
    "fullname": "浙江省",
}, {
    "code": "340000",
    "index": "A",
    "fullname": "安徽省",
}, {
    "code": "350000",
    "index": "F",
    "fullname": "福建省",
}, {
    "code": "360000",
    "index": "J",
    "fullname": "江西省",
}, {
    "code": "370000",
    "index": "S",
    "fullname": "山东省",
}, {
    "code": "410000",
    "index": "H",
    "fullname": "河南省",
}, {
    "code": "420000",
    "index": "H",
    "fullname": "湖北省",
}, {
    "code": "430000",
    "index": "H",
    "fullname": "湖南省",
}, {
    "code": "440000",
    "index": "G",
    "fullname": "广东省",
}, {
    "code": "450000",
    "index": "G",
    "name": "广西",
    "fullname": "广西壮族自治区",
}, {
    "code": "460000",
    "index": "H",
    "fullname": "海南省",
}, {
    "code": "500000",
    "index": "C",
    "fullname": "重庆市",
}, {
    "code": "510000",
    "index": "S",
    "fullname": "四川省",
}, {
    "code": "520000",
    "index": "G",
    "fullname": "贵州省",
}, {
    "code": "530000",
    "index": "Y",
    "fullname": "云南省",
}, {
    "code": "540000",
    "index": "X",
    "fullname": "西藏自治区",
}, {
    "code": "610000",
    "index": "S",
    "fullname": "陕西省",
}, {
    "code": "620000",
    "index": "G",
    "fullname": "甘肃省",
}, {
    "code": "630000",
    "index": "Q",
    "fullname": "青海省",
}, {
    "code": "640000",
    "index": "N",
    "name": "宁夏",
    "fullname": "宁夏回族自治区",
}, {
    "code": "650000",
    "index": "X",
    "name": "新疆",
    "fullname": "新疆维吾尔自治区",
}, {
    "code": "710000",
    "index": "T",
    "fullname": "台湾省",
}, {
    "code": "810000",
    "index": "X",
    "fullname": "香港特别行政区",
}, {
    "code": "820000",
    "index": "A",
    "fullname": "澳门特别行政区"
}];



const { provinces, provincesSelection } = (function() {
    let provinces = {},
        provincesSelection = {};
    provincesData.forEach(function(province) {
        if (province.name) {
            var name = province.name;
        } else {
            var name = province.fullname.replace(/(省|市|自治区|特别行政区)$/, '');
        }
        provinces[province.code.substr(0, 2)] = { name };
        if (provincesSelection[province.index]) {
            provincesSelection[province.index].push({
                "code": province.code,
                name
            });
        } else {
            provincesSelection[province.index] = [{
                "code": province.code,
                name
            }]
        }
    });
    return { provinces, provincesSelection };
}());

console.log(provinces, provinces[42], provincesSelection);