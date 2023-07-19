const addReview = document.getElementById('add-review');
const reviewForm = document.querySelector('.review-form');

// decide whether to show or hide form
if (document.body.contains(addReview)) {
    addReview.addEventListener('click', ()=>{
        if (addReview.innerHTML == '<i class="fa-solid fa-eject"></i>Cancel') {
            reviewForm.style.display = 'none';
            addReview.innerHTML = '<i class="fa-solid fa-plus"></i>Add Review';
        } else {
            addReview.innerHTML = '<i class="fa-solid fa-eject"></i>Cancel';
            reviewForm.style.display = 'block';
            document.getElementById('review').focus();
        }
    });
}

// react accordingly to rating stars
var rating = 0;
const ratingInput = document.getElementById('rating');
const reviewStars = document.querySelectorAll('.actual-review-stars i');
reviewStars.forEach((star, index1)=>{
    star.addEventListener('click', ()=>{
        rating = index1 + 1;
        ratingInput.value = rating;
        reviewStars.forEach((star, index2)=>{
            if (index1 >= index2) {
                star.classList.add("rating-active");
            } else {
                star.classList.remove('rating-active');
            }
        })
    })
});

// enlarge property image onclick
const propertyImages = document.querySelectorAll('.swiper-slide img');
const fullScreen = document.querySelector('.fullscreen');
const img = document.querySelector('.fullscreen img');
propertyImages.forEach((image)=>{
    image.addEventListener('click', ()=>{
        img.setAttribute('src', image.getAttribute('src'));
        fullScreen.style.visibility = 'visible';
        window.onscroll = function () { window.scrollTo(0, 0); };
    });
});

const exitFullScreen = document.querySelector('.fullscreen span');
exitFullScreen.addEventListener('click', ()=>{
    document.body.classList.remove('stop-scroll');
    fullScreen.style.visibility = 'hidden';
    window.onscroll = function () {};
});

// ensure rating is chosen before proceed to backend
const submitForm = document.getElementById('submit');
submitForm.addEventListener('click', ()=>{
    if (ratingInput.value=="") {
        document.querySelector('.actual-review-stars h5').style.display = 'inline-block';
    }
});

// load more reviews
const reviews = document.querySelectorAll('.review');
reviews.forEach((review, index)=>{
    if (index>1) {
        review.classList.add('fade-in');
        review.style.display = 'none';
    }
});
const loadMore = document.querySelector('.user-reviews h5');
if (loadMore != null) {
    loadMore.addEventListener('click', ()=>{
        reviews.forEach((review)=>{
            review.style.display = 'block';
        })
        loadMore.style.display = 'none';
    });
}