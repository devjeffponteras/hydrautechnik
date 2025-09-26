const gjsOwlCarousel = (editor) => {

  editor.BlockManager.add("owl-carousel", {
      label: "Carousel",
      name: "Carousel",
      content: `<div data-gjs-type="owl-carousel" class="owl-carousel image-carousel carousel-widget" data-items-xs="2" data-items-sm="3" data-items-lg="4" data-items-xl="5">

						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 1"></a>
						</div>
						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 2"></a>
						</div>
						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 3"></a>
						</div>
						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 4"></a>
						</div>
						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 5"></a>
						</div>
						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 6"></a>
						</div>
						<div data-gjs-type="owl-slide" class="oc-item">
							<a href="#"><img src="https://via.placeholder.com/400x300" alt="Image 7"></a>
						</div>

					</div>
    `,
      category: "Basic",
      attributes: { class: "fa fa-minus-square-o" },
  });

  editor.DomComponents.addType("owl-carousel", {
      isComponent: (el) => el.tagName === "DIV",
      extend: "link",
      name: "Carousel",
      model: {},
      view: {}, // Will extend the view from 'other-defined-component'
  });

};