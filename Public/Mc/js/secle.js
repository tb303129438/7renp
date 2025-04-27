(function($, doc) {
				$.init();
				$.ready(function() {
					/**
					 * 获取对象属性的值
					 * 主要用于过滤三级联动中，可能出现的最低级的数据不存在的情况，实际开发中需要注意这一点；
					 * @param {Object} obj 对象
					 * @param {String} param 属性名
					 */
					var _getParam = function(obj, param) {
						return obj[param] || '';
					};
					//普通示例
					var userPicker = new $.PopPicker();
					userPicker.setData([{
						value: '',
						text: '董事长 叶文洁'
					}, {
						value: '',
						text: '总经理 艾AA'
					}, {
						value: '',
						text: '罗辑'
					}, {
						value: '',
						text: '云天明'
					}, {
						value: '',
						text: '史强'
					}, {
						value: '',
						text: '章北海'
					}, {
						value: '',
						text: '庄颜'
					}, {
						value: '',
						text: '关一帆'
					}, {
						value: '',
						text: '智子'
					}, {
						value: '', 
						text: '歌者'
					}]);
					var showUserPickerButton = doc.getElementById('showUserPicker');
					var userResult = doc.getElementById('select');
					showUserPickerButton.addEventListener('tap', function(event) {
						userPicker.show(function(items) {
							userResult.value = JSON.stringify(items[0].text);
							//返回 false 可以阻止选择框的关闭
							//return false;
						});
					}, false);
					
				});
			})(mui, document);