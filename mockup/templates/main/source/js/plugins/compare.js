// comparison
var compareArray = {

	init: function (o) {

		var i, l, _this = this;

		this.o = o = {
			table: o.table || null, // Сама таблица сравнение
			button: o.button || null, // Кнопки переключатели
			activeClass: o.activeClass || "active", // Класс для активной ссылки
			indexCols: o.indexCols || 0, // Индекс с которого начинается перебор столбцов в строке
			indexRows: o.indexRows || 0, // Индекс с которого начинается перебор строк
			zebra: o.zebra || false, // Включение зебры таблцы при переключении кнопок
			zebraClass: o.zebraClass || "odd", // Класс строки для фона зебры таблицы
			callback: o.callback || function () {} // Обратная функция
		}

		if (!o.table || o.button.length == 0) {
			return false;
		}

		this.tr = o.table.getElementsByTagName("tr");

		for (i = 0, l = o.button.length; i < l; i += 1) {

			o.button[i].onclick = function () {

				for (var i = 0, l = o.button.length; i < l; i += 1) {
					if (o.button[i] !== this) {
						_this.removeClass(o.button[i], o.activeClass);
					}
				}
				_this.addClass(this, o.activeClass);
				_this.hideShowRows(this.getAttribute("compare"));
				var visible = $('.swiper-container-comparison tr.characteristic__row');
				var visibleMain = $('.comparison__main .characteristic__row');
				var visibleArrIndex = [];
				var visibleMainArrIndex = [];
				$(visible).each(function (index, el) {
					if ($(el).css('display') != 'none') {

						// $(el).prev().prev('.characteristic__title').show();
						$(el).prevAll('.characteristic__title').first().show();
						visibleArrIndex.push(index);
					}
				})
				$(visibleMain).each(function (index, el) {
					visibleMainArrIndex.push(index, el);
					$(visibleArrIndex).each(function (indexInner, elInner) {
						if (index == elInner) {
							$(el).show();
							// $(el).prev().prev('.characteristic__title').show();
							$(el).prevAll('.characteristic__title').first().show();

						}
					});


				})


				// console.log(visibleArrIndex);
				// console.log(visibleMainArrIndex);

				return false;

			}
		}

	},

	hideShowRows: function (r) {
		var i, l, j, n, td, text = [];
		if (r == "DIFF") {
			for (i = this.o.indexRows, l = this.tr.length; i < l; i += 1) {
				td = this.tr[i].getElementsByTagName("td");
				for (j = this.o.indexCols, n = td.length; j < n; j += 1) {
					text.push(td[j].innerHTML.toLowerCase().replace(/\s+/g, ""))
					// console.log(text)

				}
				if (this.array(text[0], text)) {
					// console.log(text)
					this.tr[i].style.display = "none";
				}
				text.length = 0;

			}
		} else
		if (r == "ALL") {
			for (i = this.o.indexRows, l = this.tr.length; i < l; i += 1) {
				this.tr[i].style.display = "flex";
			}
		}
		if (this.o.zebra) {
			this.zebraTable();
		}
		this.o.callback.call(this);
	},
	array: function (n, arr) {
		var f = true,
			i, l;
		for (i = 1, l = arr.length; i < l; i += 1) {
			if (n != arr[i]) {
				f = false;
			}
		}
		return f;
	},
	hasClass: function (o, cls) {
		return new RegExp("(\\s|^)" + cls + "(\\s|$)").test(o.className);
	},
	addClass: function (o, cls) {
		if (!this.hasClass(o, cls)) {
			o.className += (o.className ? " " : "") + cls;
		}
	},
	removeClass: function (o, cls) {
		if (this.hasClass(o, cls)) {
			o.className = o.className.replace(new RegExp("(\\s|^)" + cls + "(\\s|$)"), " ").replace(/^\s+|\s+$/g, "")
		}
	},
	zebraTable: function () {
		var i, l, c = 0;
		for (i = this.o.indexRows, l = this.tr.length; i < l; i += 1) {
			this.removeClass(this.tr[i], this.o.zebraClass);
			if (c % 2 == 1) {
				this.addClass(this.tr[i], this.o.zebraClass);
			}
			if (this.tr[i].style.display != "none") {
				c = c + 1;
			}
		}
	}
}


// comparison end