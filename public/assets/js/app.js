(function ($) {
  'use strict';

  if (!$) {
    return;
  }

  // sidebar submenu collapsible js
  $(".sidebar-menu .dropdown").on("click", function(){
    var item = $(this);
    item.siblings(".dropdown").children(".sidebar-submenu").slideUp();

    item.siblings(".dropdown").removeClass("dropdown-open");

    item.siblings(".dropdown").removeClass("open");

    item.children(".sidebar-submenu").slideToggle();

    item.toggleClass("dropdown-open");
  });

  $(".sidebar-toggle").on("click", function(){
    $(this).toggleClass("active");
    $(".sidebar").toggleClass("active");
    $(".dashboard-main").toggleClass("active");
  });

  $(".sidebar-mobile-toggle").on("click", function(){
    $(".sidebar").addClass("sidebar-open");
    $("body").addClass("overlay-active");
  });

  $(".sidebar-close-btn").on("click", function(){
    $(".sidebar").removeClass("sidebar-open");
    $("body").removeClass("overlay-active");
  });

  //to keep the current page active
  $(function () {
    for (
      var nk = window.location,
        o = $("ul#sidebar-menu a")
          .filter(function () {
            return this.href == nk;
          })
          .addClass("active-page") // anchor
          .parent()
          .addClass("active-page");
      ;

    ) {
      // li
      if (!o.is("li")) break;
      o = o.parent().addClass("show").parent().addClass("open");
    }
  });

/**
* Utility function to calculate the current theme setting based on localStorage.
*/
function calculateSettingAsThemeString({ localStorageTheme }) {
  if (localStorageTheme !== null) {
    return localStorageTheme;
  }
  return "light"; // default to light theme if nothing is stored
}

/**
* Utility function to update the button text and aria-label.
*/
function updateButton({ buttonEl, isDark }) {
  const newCta = isDark ? "dark" : "light";
  buttonEl.setAttribute("aria-label", newCta);
  buttonEl.innerText = newCta;
}

/**
* Utility function to update the theme setting on the html tag.
*/
function updateThemeOnHtmlEl({ theme }) {
  document.querySelector("html").setAttribute("data-theme", theme);
}

/**
* 1. Grab what we need from the DOM and system settings on page load.
*/
const button = document.querySelector("[data-theme-toggle]");
const localStorageTheme = localStorage.getItem("theme");

/**
* 2. Work out the current site settings.
*/
let currentThemeSetting = calculateSettingAsThemeString({ localStorageTheme });

/**
* 3. If the button exists, update the theme setting and button text according to current settings.
*/
if (button) {
  updateButton({ buttonEl: button, isDark: currentThemeSetting === "dark" });
  updateThemeOnHtmlEl({ theme: currentThemeSetting });

  /**
  * 4. Add an event listener to toggle the theme.
  */
  button.addEventListener("click", (event) => {
    const newTheme = currentThemeSetting === "dark" ? "light" : "dark";

    localStorage.setItem("theme", newTheme);
    updateButton({ buttonEl: button, isDark: newTheme === "dark" });
    updateThemeOnHtmlEl({ theme: newTheme });

    currentThemeSetting = newTheme;
  });
} else {
  // If no button is found, just apply the current theme to the page
  updateThemeOnHtmlEl({ theme: currentThemeSetting });
}


// =========================== Table Header Checkbox checked all js Start ================================
$('#selectAll').on('change', function () {
  $('.form-check .form-check-input').prop('checked', $(this).prop('checked')); 
}); 

  // Remove Table Tr when click on remove btn start
  $('.remove-btn').on('click', function () {
    $(this).closest('tr').remove(); 

    // Check if the table has no rows left
    if ($('.table tbody tr').length === 0) {
      $('.table').addClass('bg-danger');

      // Show notification
      $('.no-items-found').show();
    }
  });
  // Remove Table Tr when click on remove btn end
})(window.jQuery);

document.addEventListener("DOMContentLoaded", () => {
  if (
    typeof window.gsap === "undefined" ||
    typeof window.ScrollTrigger === "undefined"
  ) {
    return;
  }

  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (prefersReducedMotion) {
    return;
  }

  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  gsap.registerPlugin(ScrollTrigger);
  const isDesktopPointer = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  const isMobileView = window.matchMedia("(max-width: 767.98px)").matches;
  const homepageSections = [
    ".bb-hero-section",
    ".bb-section-wrapper",
    ".section-what-we-do",
    ".section-who-we-serve",
    ".section-key-highlights",
  ];

  const uniqueElements = (elements) => {
    const seen = new Set();

    return elements.filter((element) => {
      if (!element || seen.has(element)) {
        return false;
      }

      seen.add(element);
      return true;
    });
  };

  const isHomepageElement = (element) => {
    return homepageSections.some((selector) => element.closest(selector));
  };

  const publicSections = uniqueElements(
    gsap
      .utils
      .toArray("main section")
      .filter((section) =>
        section.querySelector("h1, h2, h3, p, img, iframe, [class*='card'], [class*='__card']")
      )
  );

  publicSections.forEach((section, index) => {
    section.classList.add("bb-motion-section");

    if (index % 2 === 0) {
      section.classList.add("bb-motion-section--alt");
    }
  });

  const splitWords = (element) => {
    if (!element || element.dataset.gsapSplit === "done") {
      return [];
    }

    const transformNode = (node) => {
      if (node.nodeType === Node.TEXT_NODE) {
        const fragment = document.createDocumentFragment();
        const parts = node.textContent.split(/(\s+)/);

        parts.forEach((part) => {
          if (!part) {
            return;
          }

          if (/^\s+$/.test(part)) {
            fragment.appendChild(document.createTextNode(part));
            return;
          }

          const outer = document.createElement("span");
          outer.className = "bb-split-word";

          const inner = document.createElement("span");
          inner.className = "bb-split-word-inner";
          inner.textContent = part;

          outer.appendChild(inner);
          fragment.appendChild(outer);
        });

        return fragment;
      }

      if (node.nodeType === Node.ELEMENT_NODE) {
        const clone = node.cloneNode(false);
        Array.from(node.childNodes).forEach((child) => {
          clone.appendChild(transformNode(child));
        });
        return clone;
      }

      return node.cloneNode(true);
    };

    const transformed = document.createDocumentFragment();
    Array.from(element.childNodes).forEach((child) => {
      transformed.appendChild(transformNode(child));
    });

    element.innerHTML = "";
    element.appendChild(transformed);
    element.dataset.gsapSplit = "done";

    return gsap.utils.toArray(element.querySelectorAll(".bb-split-word-inner"));
  };

  const fadeInOnScroll = (targets, options = {}) => {
    const elements = gsap.utils.toArray(targets);
    if (!elements.length) {
      return;
    }

    gsap.set(elements, {
      autoAlpha: 0,
      y: options.y ?? 32,
      scale: options.scale ?? 1,
      transformOrigin: "50% 50%",
      willChange: "transform, opacity",
    });

    gsap.to(elements, {
      autoAlpha: 1,
      y: 0,
      scale: 1,
      duration: options.duration ?? 0.9,
      ease: options.ease ?? "power3.out",
      stagger: options.stagger ?? 0.12,
      clearProps: "willChange",
      scrollTrigger: {
        trigger: options.trigger ?? elements[0],
        start: options.start ?? "top 82%",
        once: true,
      },
    });
  };

  const animateSplitHeading = (element, options = {}) => {
    const words = splitWords(element);
    if (!words.length) {
      return;
    }

    gsap.set(words, {
      autoAlpha: 0,
      yPercent: options.yPercent ?? 110,
      rotate: options.rotate ?? 3,
      willChange: "transform, opacity",
    });

    gsap.to(words, {
      autoAlpha: 1,
      yPercent: 0,
      rotate: 0,
      duration: options.duration ?? 0.82,
      stagger: options.stagger ?? 0.04,
      ease: options.ease ?? "power3.out",
      clearProps: "willChange",
      scrollTrigger: {
        trigger: options.trigger ?? element,
        start: options.start ?? "top 84%",
        once: true,
      },
    });
  };

  const animateHeadingDropIn = (element, options = {}) => {
    if (!element) {
      return;
    }

    gsap.set(element, {
      autoAlpha: 0,
      y: options.y ?? -72,
      willChange: "transform, opacity",
    });

    gsap.to(element, {
      autoAlpha: 1,
      y: 0,
      duration: options.duration ?? 1,
      ease: options.ease ?? "power3.out",
      clearProps: "willChange",
      scrollTrigger: options.scrollTrigger === false
        ? undefined
        : {
            trigger: options.trigger ?? element,
            start: options.start ?? "top 84%",
            once: true,
          },
    });
  };

  const heroHeadline = document.querySelector(".bb-hero-headline");
  const heroParagraphs = gsap.utils.toArray(".bb-hero-desc");
  const heroButtons = gsap.utils.toArray(".bb-cta-group a");
  const heroImage = document.querySelector(".bb-hero-img-wrapper");
  const heroDots = document.querySelector(".bb-dot-grid");
  const heroWords = splitWords(heroHeadline);

  if (heroHeadline || heroParagraphs.length || heroButtons.length || heroImage) {
    const heroTimeline = gsap.timeline({
      defaults: {
        duration: 0.9,
        ease: "power3.out",
      },
    });

    if (heroHeadline) {
      heroTimeline.from(heroHeadline, {
        autoAlpha: 0,
        y: -78,
        duration: 1,
      });
    }

    if (heroParagraphs.length) {
      heroTimeline.from(
        heroParagraphs,
        {
          autoAlpha: 0,
          y: 24,
          stagger: 0.14,
        },
        "-=0.55"
      );
    }

    if (heroButtons.length) {
      heroTimeline.from(
        heroButtons,
        {
          autoAlpha: 0,
          y: 18,
          stagger: 0.1,
        },
        "-=0.45"
      );
    }

    if (heroImage) {
      heroTimeline.from(
        heroImage,
        {
          autoAlpha: 0,
          x: 42,
          scale: 0.96,
        },
        "-=0.85"
      );
    }

    if (heroDots) {
      gsap.fromTo(
        heroDots,
        {
          autoAlpha: 0,
          scale: 0.75,
          rotate: -8,
        },
        {
          autoAlpha: 1,
          scale: 1,
          rotate: 0,
          duration: 1.1,
          ease: "power2.out",
          delay: 0.3,
        }
      );
    }

    if (heroImage) {
      gsap.to(heroImage, {
        yPercent: isMobileView ? -3 : -8,
        ease: "none",
        scrollTrigger: {
          trigger: ".bb-hero-section",
          start: "top top",
          end: "bottom top",
          scrub: 1,
        },
      });
    }
  }

  const navbar = document.querySelector(".bb-navbar");
  if (navbar) {
    ScrollTrigger.create({
      start: 20,
      end: "max",
      onUpdate: (self) => {
        navbar.classList.toggle("bb-navbar-scrolled", self.scroll() > 24);
      },
    });
  }

  const underlineSelectors = [".bb-heading-underline", ".wws-underline"];
  underlineSelectors.forEach((selector) => {
    gsap.utils.toArray(selector).forEach((element) => {
      gsap.from(element, {
        scaleX: 0,
        transformOrigin: "left center",
        duration: 0.8,
        ease: "power2.out",
        scrollTrigger: {
          trigger: element,
          start: "top 88%",
          once: true,
        },
      });
    });
  });

  fadeInOnScroll(".bb-heading-underline", {
    trigger: ".bb-section-wrapper",
    y: 18,
    duration: 0.7,
  });

  fadeInOnScroll(".bb-feature-card", {
    trigger: ".bb-section-wrapper .row",
    y: 42,
    stagger: 0.15,
  });

  fadeInOnScroll(".wwd-desc, .wwd-solutions-label", {
    trigger: ".section-what-we-do",
    y: 30,
    stagger: 0.14,
  });

  fadeInOnScroll(".wwd-crop-item", {
    trigger: ".wwd-crops-grid",
    y: 24,
    stagger: 0.1,
    duration: 0.75,
  });

  fadeInOnScroll(".wws-underline", {
    trigger: ".section-who-we-serve",
    y: 26,
    stagger: 0.1,
  });

  fadeInOnScroll(".wws-card", {
    trigger: ".wws-cards-row",
    y: 38,
    stagger: 0.12,
  });

  fadeInOnScroll(".kh-subtitle", {
    trigger: ".section-key-highlights",
    y: 26,
    stagger: 0.1,
  });

  fadeInOnScroll(".kh-card", {
    trigger: ".kh-cards-row",
    y: 36,
    stagger: 0.14,
  });

  gsap.utils.toArray(".bb-icon-badge, .wws-icon-circle, .kh-icon-circle").forEach((element, index) => {
    gsap.to(element, {
      y: index % 2 === 0 ? -7 : -4,
      duration: 2.1 + (index % 3) * 0.25,
      ease: "sine.inOut",
      repeat: -1,
      yoyo: true,
    });
  });

  if (isDesktopPointer) {
    gsap
      .utils
      .toArray("main a[class*='btn'], main a[class*='cta'], main button[class*='btn'], main button[class*='cta'], main button[class*='submit']")
      .forEach((button) => {
      const moveX = gsap.quickTo(button, "x", { duration: 0.35, ease: "power3.out" });
      const moveY = gsap.quickTo(button, "y", { duration: 0.35, ease: "power3.out" });

      button.addEventListener("pointermove", (event) => {
        const rect = button.getBoundingClientRect();
        const x = event.clientX - rect.left - rect.width / 2;
        const y = event.clientY - rect.top - rect.height / 2;

        moveX(x * 0.12);
        moveY(y * 0.18);
      });

      button.addEventListener("pointerleave", () => {
        moveX(0);
        moveY(0);
      });
    });

    gsap
      .utils
      .toArray("main [class*='card'], main [class*='__card'], main [class*='feature-item'], main [class*='__box']")
      .filter((card) => !card.closest(".pd-page"))
      .forEach((card) => {
      const cardX = gsap.quickTo(card, "x", { duration: 0.4, ease: "power3.out" });
      const cardY = gsap.quickTo(card, "y", { duration: 0.4, ease: "power3.out" });

      card.addEventListener("pointermove", (event) => {
        const rect = card.getBoundingClientRect();
        const pointerX = (event.clientX - rect.left) / rect.width;
        const pointerY = (event.clientY - rect.top) / rect.height;

        cardX((pointerX - 0.5) * 8);
        cardY((pointerY - 0.5) * -8);
      });

      card.addEventListener("pointerleave", () => {
        cardX(0);
        cardY(0);
      });
    });
  }

  const genericHeadings = uniqueElements(
    gsap
      .utils
      .toArray("main section h1, main section h2")
      .filter((element) => !isHomepageElement(element))
  );

  genericHeadings.forEach((heading) => {
    animateSplitHeading(heading, {
      start: "top 86%",
      yPercent: 105,
      rotate: 2,
      stagger: 0.035,
      duration: 0.78,
    });
  });

  const genericTextBlocks = uniqueElements(
    gsap
      .utils
      .toArray(
        "main section h3, main section h4, main section h5, main section p, main section li, main section [class*='__badge'], main section [class*='__subtext'], main section [class*='__desc'], main section [class*='__title'], main section [class*='__heading'], main section [class*='__label'], main section [class*='__header']"
      )
      .filter((element) => !isHomepageElement(element))
  );

  fadeInOnScroll(genericTextBlocks, {
    y: 22,
    duration: 0.72,
    stagger: 0.03,
  });

  const genericCards = uniqueElements(
    gsap
      .utils
      .toArray(
        "main section [class*='__card'], main section [class*='__item'], main section [class*='__box'], main section [class*='__feature'], main section [class*='__info-card'], main section [class*='__flow-box'], main section [class*='__stat-card']"
      )
      .filter((element) => !isHomepageElement(element) && !element.closest(".pd-page"))
  );

  fadeInOnScroll(genericCards, {
    y: 42,
    scale: 0.98,
    duration: 0.82,
    stagger: 0.08,
  });

  const genericButtons = uniqueElements(
    gsap
      .utils
      .toArray("main section a[class], main section button[class]")
      .filter((element) => {
        if (isHomepageElement(element)) {
          return false;
        }

        const className = element.className || "";
        return /(btn|cta|submit|link)/i.test(className);
      })
  );

  fadeInOnScroll(genericButtons, {
    y: 18,
    duration: 0.65,
    stagger: 0.06,
  });

  const genericImages = uniqueElements(
    gsap
      .utils
      .toArray("main section img, main section iframe")
      .filter((element) => {
        if (isHomepageElement(element)) {
          return false;
        }

        const className = element.className || "";
        const src = element.getAttribute("src") || "";
        const isTinyIcon = /(icon|check|badge)/i.test(className) || /(icon|check)/i.test(src);
        const parentWrapper = element.closest(".bb-hero-img-wrapper, .wwd-image-wrapper");
        return !isTinyIcon && !parentWrapper;
      })
  );

  fadeInOnScroll(genericImages, {
    y: 30,
    scale: 0.97,
    duration: 0.9,
    stagger: 0.06,
  });

  genericImages.forEach((element) => {
    if (element.tagName.toLowerCase() !== "img") {
      return;
    }

    gsap.to(element, {
      yPercent: -6,
      ease: "none",
      scrollTrigger: {
        trigger: element,
        start: "top bottom",
        end: "bottom top",
        scrub: 1,
      },
    });
  });

  publicSections.forEach((section) => {
    const cinematicText = uniqueElements(
      gsap
        .utils
        .toArray(
          section.querySelectorAll(
            "h1, h2, p, [class*='__heading'], [class*='__desc'], [class*='__subtext'], [class*='__title']"
          )
        )
    );
    const cinematicCards = uniqueElements(
      gsap
        .utils
        .toArray(
          section.querySelectorAll("[class*='card'], [class*='__card'], [class*='__item'], [class*='__box']")
        )
        .filter((element) => !element.closest(".pd-page"))
    );
    const cinematicMedia = uniqueElements(
      gsap
        .utils
        .toArray(section.querySelectorAll("img, iframe"))
        .filter((element) => {
          const className = element.className || "";
          const src = element.getAttribute("src") || "";
          const parentWrapper = element.closest(".bb-hero-img-wrapper, .wwd-image-wrapper");
          return !/(icon|check|badge)/i.test(className) && !/(icon|check)/i.test(src) && !parentWrapper;
        })
    );

    ScrollTrigger.create({
      trigger: section,
      start: "top 72%",
      end: "bottom 36%",
      onEnter: () => section.classList.add("bb-motion-active"),
      onEnterBack: () => section.classList.add("bb-motion-active"),
      onLeaveBack: () => section.classList.remove("bb-motion-active"),
    });

    gsap.fromTo(
      section,
      {
        autoAlpha: 0.88,
        y: 56,
      },
      {
        autoAlpha: 1,
        y: 0,
        ease: "none",
        scrollTrigger: {
          trigger: section,
          start: "top 92%",
          end: "top 44%",
          scrub: 1,
        },
      }
    );

    if (cinematicText.length) {
      gsap.fromTo(
        cinematicText,
        {
          y: 26,
          autoAlpha: 0.2,
        },
        {
          y: 0,
          autoAlpha: 1,
          stagger: 0.05,
          ease: "none",
          scrollTrigger: {
            trigger: section,
            start: "top 86%",
            end: "top 38%",
            scrub: 1,
          },
        }
      );
    }

    if (cinematicCards.length) {
      gsap.fromTo(
        cinematicCards,
        {
          y: 48,
          autoAlpha: 0.3,
          scale: 0.965,
        },
        {
          y: 0,
          autoAlpha: 1,
          scale: 1,
          stagger: 0.08,
          ease: "none",
          scrollTrigger: {
            trigger: section,
            start: "top 88%",
            end: "top 34%",
            scrub: 1.1,
          },
        }
      );
    }

    cinematicMedia.forEach((element) => {
      gsap.fromTo(
        element,
        {
          yPercent: 10,
          scale: 1.08,
          autoAlpha: 0.45,
        },
        {
          yPercent: -6,
          scale: 1,
          autoAlpha: 1,
          ease: "none",
          scrollTrigger: {
            trigger: section,
            start: "top bottom",
            end: "bottom top",
            scrub: 1.2,
          },
        }
      );
    });
  });

  [
    ".bb-section-heading",
    ".wwd-heading",
    ".wws-title",
    ".kh-title",
  ].forEach((selector, index) => {
    gsap.utils.toArray(selector).forEach((heading) => {
      animateHeadingDropIn(heading, {
        trigger: heading.closest("section") ?? heading,
        start: index === 0 ? "top 98%" : "top 82%",
        y: index === 0 ? -90 : -68,
        duration: index === 0 ? 1.05 : 0.95,
      });
    });
  });
});
