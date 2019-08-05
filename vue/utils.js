// 工具类

/**
 * 生成随机字符串
 * @param len {number}
 * @default len = 16
 * @returns {string}
 */
function randStr(len) {
	var len = len || 16;
	var $chars = "ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678";
	var maxPos = $chars.length;
	var str = "";
	for (var i = 0; i < len; i++) {
		str += $chars.charAt(Math.floor(Math.random()*maxPos));
	}
	return str;
}

/**
 * 生成随机数
 * @param {number} figures 
 * @returns {number}
 */
function randNum(figures) {
	var figures = figures || 3;
	var max = 0,min = 0;
	max = Math.pow(10, figures)-1;
	min = Math.pow(10, figures-1);
	return parseInt(Math.random()*(max-min+1)+min,10);
}