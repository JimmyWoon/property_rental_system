/*=============== SWIPER POPULAR ===============*/
var swiperPopular = new Swiper(".popularContainer", {
    spaceBetween: 32,
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: 'auto',

    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev"
    }
});

/*=============== VALUE ACCORDION ===============*/
const accordionItems = document.querySelectorAll('.valueAccordion-item');

accordionItems.forEach((item) => {
    const accordionHeader = item.querySelector('.valueAccordion-header')

    accordionHeader.addEventListener('click', () => {
        const openItem = document.querySelectorAll('.accordion-open')

        toggleItem(item)

        if (openItem && openItem != item) {
            toggleItem(openItem)
        }
    })
});

const toggleItem = (item) => {
    const accordionContent = item.querySelector('.valueAccordion-content')

    if(item.classList.contains('accordion-open')) {
        accordionContent.removeAttribute('style')
        item.classList.remove('accordion-open')
    } else {
        accordionContent.style.height = accordionContent.scrollHeight + 'px'
        item.classList.add('accordion-open')    
    }
};