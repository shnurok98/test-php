let headers = new Headers();
headers.append("Content-Type", "application/json");


// заменить на адрес сервера php
const serverName = 'http://indexbox.loc';




window.onload = function(){
	onLoadPage();
}

function onFilter(){
	let form = document.forms.filterForm;
	
	let views = form.views.value === "" ? '' : form.views.value;
	let href = form.product.value === "0" ? '' : form.product.value;
	let time_create = form.time_create.value == "" ? '' : form.time_create.value;
	let limit = form.cntArticle.value;
	
	let q = '?';
	if (views) {
		switch(views[0]){
			case '=':
				views = views.replace('=', 'eq');
				break;
			case '>':
				views = views.replace('>', 'gt');
				break;
			case '<':
				views = views.replace('<', 'lt');
				break;
		}
		views = 'views=' + views + '&';
	}
	if (href) href = 'href=' + href + '&';
	if (time_create) {
		time_create = Date.parse(time_create);
		time_create = 'time_create=' + time_create + '&';
	}
	if (limit) limit = 'limit=' + limit + '&';

	q += views + href + time_create + limit;
	q = q.substring(0, q.length - 1);
	console.log(q);
	getFiltered(q);
}

async function onLoadPage() {
	try{
		// получаем статьи
		let res = await fetch(`${serverName}/article`);
		let data = await res.text();
		let content = document.querySelector('.content');
		content.innerHTML = data;

		// получаем продукты для селекта
		let res2 = await fetch(`${serverName}/product`);
		let data2 = await res2.json();
		let slProduct = document.getElementById('slProduct');

		for(let i = 0; i < data2.length; i++) {
			opt = document.createElement('option');
			opt.value = data2[i]['href'];
			opt.innerHTML = data2[i].name;
			slProduct.append(opt);
		}
		
	}catch(err){
		console.log(err);
	}
}

async function getFiltered(q) {
	try{
		// получаем статьи
		let res = await fetch(`${serverName}/article/` + q);
		let data = await res.text();
		let content = document.querySelector('.content');
		content.innerHTML = data;
		
		// if ( ! content.childNodes[0].hasChildNodes()) {
		// 	content.innerHTML = 'Нет подходящих статей';
		// }
		
	}catch(err){
		console.log(err);
	}
}


async function onGetArticle(elm) {
	console.log(elm.dataset.id)
	let id = parseInt(elm.dataset.id);
	try{
		// получаем статью
		let res = await fetch(`${serverName}/article/${id}`);
		let data = await res.text();
		let content = document.querySelector('.content');
		content.innerHTML = data;
		
	}catch(err){
		console.log(err);
	}
}