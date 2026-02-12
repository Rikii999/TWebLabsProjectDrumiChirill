//---Menu

const burger = document.querySelector('.menu-icon');
const menu = document.querySelector('.menu');
const body = document.body

if (burger && menu) {
	burger.addEventListener('click', () => {
		burger.classList.toggle('_active');
		menu.classList.toggle('_active');
		body.classList.toggle('_lock');
	})
}

//---Filter dropdown

const filter = document.querySelector('.filter');

if (filter) {
	const items = filter.querySelectorAll('.block-filter')

	items.forEach(item => {
		item.addEventListener('click', event => {
			item.querySelector('.block-filter__dropdown').classList.toggle('_active');
			item.querySelector('.block-filter__icon').classList.toggle('_active');

			if (event.target.classList.contains('block-filter__item')) {
				item.querySelector('.block-filter__value').textContent = event.target.textContent;
			}
		})
	})
}

//---Swiper

const popularSlider = new Swiper('.popular-slider', {
	spaceBetween: 20,
	slidesPerView: 1,
	// Navigation arrows
	navigation: {
		nextEl: '.popular-slider-next',
		prevEl: '.popular-slider-prev',
	},
	breakpoints: {
		992: {
			slidesPerView: 3,
		},
		660: {
			slidesPerView: 2,
		}
	}
});

const reviewsSlider = new Swiper('.slider-reviews', {
	spaceBetween: 20,
	slidesPerView: 1,
	autoHeight: true,
	navigation: {
		nextEl: '.slider-reviews-next',
		prevEl: '.slider-reviews-prev',
	},
});

//---Gallery

const galleryItems = document.querySelectorAll(".gallery__item");

if (galleryItems.length > 0) {
	galleryItems.forEach(item => {
		new Swiper(item, {
			slidesPerView: 1, 
			autoplay: {
				delay: 5000,
			},
			effect: 'fade',
		})
	})
}

// Логика выпадающих списков (Dropdowns)
const filterBlocks = document.querySelectorAll('.block-filter');

filterBlocks.forEach(block => {
    const btn = block.querySelector('.block-filter__button');
    const items = block.querySelectorAll('.block-filter__item');
    const valueDisplay = block.querySelector('.block-filter__value');

    btn.addEventListener('click', () => {
        block.classList.toggle('_active');
    });

    items.forEach(item => {
        item.addEventListener('click', () => {
            valueDisplay.innerText = item.innerText;
            block.classList.remove('_active');
        });
    });
});

// Кнопка поиска
const searchBtn = document.querySelector('.filter__btn button');
searchBtn?.addEventListener('click', () => {
    const purpose = document.querySelector('.block-filter:nth-child(1) .block-filter__value').innerText;
    const location = document.querySelector('.block-filter:nth-child(2) .block-filter__value').innerText;
    
    alert(`Searching for: ${purpose} in ${location}`);
    // Здесь можно добавить фильтрацию карточек на странице
});