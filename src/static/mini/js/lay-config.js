/**
 * date:2019/08/16
 * author:Mr.Chung
 * description:此处放layui自定义扩展
 * version:2.0.4
 */

window.rootPath = (function (src) {
    src = document.scripts[document.scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();

layui.config({
    base: rootPath + "lay-module/",
    version: true
}).extend({
    miniAdmin: "layuimini/miniAdmin", // layuimini后台扩展
    miniMenu: "layuimini/miniMenu", // layuimini菜单扩展
    miniTab: "layuimini/miniTab", // layuimini tab扩展
    miniTheme: "layuimini/miniTheme", // layuimini 主题扩展
    miniTongji: "layuimini/miniTongji", // layuimini 统计扩展
    step: 'step-lay/step', // 分步表单扩展
    treetable: 'treetable-lay/treetable', //table树形扩展
    treeTable: 'treeTable/treeTable', //table树形扩展
    tableSelect: 'tableSelect/tableSelect', // table选择扩展
    iconPickerFa: 'iconPicker/iconPickerFa', // fa图标选择扩展
    echarts: 'echarts/echarts', // echarts图表扩展
    echartsTheme: 'echarts/echartsTheme', // echarts图表主题扩展
    wangEditor: 'wangEditor/wangEditor', // wangEditor富文本扩展
    layarea: 'layarea/layarea', //  省市县区三级联动下拉选择器
    openTable: 'openTable/openTable', //  折叠表格
    xmSelect: 'xmSelect/xm-select', //  下拉选择
});

let isClick = true;
// 封装请求
function request($, url, formData, successCallback, method = 'post')
{
    if (formData instanceof FormData) {
        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: successCallback,
            error: function () {
                isClick = true;
                console.log('请求错误');
                console.log(url);
                console.log(formData);
            }
        });
    } else {
        $.ajax({
            url: url,
            type: method,
            data: formData,
            dataType: "json",
            success: successCallback,
            error: function () {
                isClick = true;
                console.log('请求错误');
                console.log(url);
                console.log(formData);
            }
        });
    }
}