(function () {
    var hero = document.getElementById("hero");
    var track = document.getElementById("heroTrack");
    var dotsBox = document.getElementById("heroDots");
    var prevButton = document.getElementById("heroPrev");
    var nextButton = document.getElementById("heroNext");
    var toggleButton = document.getElementById("heroToggle");

    if (!hero || !track || !dotsBox) {
        return;
    }

    var slides = Array.prototype.slice.call(track.children);

    if (!slides.length) {
        return;
    }

    var duration = parseInt(hero.getAttribute("data-interval"), 10) || 6000;
    var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    var current = 0;
    var elapsed = 0;
    var lastTime = 0;
    var userPaused = false;
    var hoverPaused = false;
    var focusPaused = false;
    var touchStartX = 0;

    var dots = slides.map(function (slide, index) {
        var dot = document.createElement("button");

        dot.type = "button";
        dot.className = "hero-dot";
        dot.setAttribute("aria-label", "Show slide " + (index + 1));

        dot.addEventListener("click", function () {
            goTo(index);
        });

        dotsBox.appendChild(dot);

        return dot;
    });

    function isPaused() {
        return userPaused || hoverPaused || focusPaused;
    }

    function syncPauseState() {
        hero.classList.toggle("is-paused", isPaused());
        track.setAttribute("aria-live", isPaused() ? "polite" : "off");

        if (toggleButton) {
            toggleButton.textContent = userPaused ? "Play" : "Pause";
            toggleButton.setAttribute(
                "aria-label",
                userPaused ? "Play automatic slideshow" : "Pause automatic slideshow"
            );
        }
    }

    function goTo(index) {
        current = (index + slides.length) % slides.length;
        elapsed = 0;

        track.style.transform = "translateX(-" + current * 100 + "%)";

        slides.forEach(function (slide, i) {
            var active = i === current;

            slide.classList.toggle("is-active", active);

            if (active) {
                slide.removeAttribute("inert");
            } else {
                slide.setAttribute("inert", "");
            }
        });

        dots.forEach(function (dot, i) {
            var active = i === current;

            dot.classList.toggle("active", active);
            dot.setAttribute("aria-current", active ? "true" : "false");
            dot.style.removeProperty("--p");
        });
    }

    function frame(now) {
        var delta = lastTime ? Math.min(now - lastTime, 100) : 0;

        lastTime = now;

        if (!isPaused() && !document.hidden) {
            elapsed += delta;

            if (elapsed >= duration) {
                goTo(current + 1);
            } else {
                dots[current].style.setProperty("--p", (elapsed / duration).toFixed(4));
            }
        }

        window.requestAnimationFrame(frame);
    }

    if (prevButton) {
        prevButton.addEventListener("click", function () {
            goTo(current - 1);
        });
    }

    if (nextButton) {
        nextButton.addEventListener("click", function () {
            goTo(current + 1);
        });
    }

    if (toggleButton) {
        toggleButton.addEventListener("click", function () {
            userPaused = !userPaused;
            syncPauseState();
        });
    }

    hero.addEventListener("pointerenter", function (event) {
        if (event.pointerType === "mouse") {
            hoverPaused = true;
            syncPauseState();
        }
    });

    hero.addEventListener("pointerleave", function (event) {
        if (event.pointerType === "mouse") {
            hoverPaused = false;
            syncPauseState();
        }
    });

    hero.addEventListener("focusin", function (event) {
        if (event.target.matches(":focus-visible")) {
            focusPaused = true;
            syncPauseState();
        }
    });

    hero.addEventListener("focusout", function (event) {
        if (!hero.contains(event.relatedTarget)) {
            focusPaused = false;
            syncPauseState();
        }
    });

    hero.addEventListener("keydown", function (event) {
        if (event.key === "ArrowLeft") {
            goTo(current - 1);
        } else if (event.key === "ArrowRight") {
            goTo(current + 1);
        }
    });

    hero.addEventListener(
        "touchstart",
        function (event) {
            touchStartX = event.changedTouches[0].clientX;
        },
        { passive: true }
    );

    hero.addEventListener(
        "touchend",
        function (event) {
            var distance = event.changedTouches[0].clientX - touchStartX;

            if (Math.abs(distance) > 40) {
                goTo(distance < 0 ? current + 1 : current - 1);
            }
        },
        { passive: true }
    );

    goTo(0);
    syncPauseState();

    if (reduceMotion) {
        hero.classList.add("is-static");

        if (toggleButton) {
            toggleButton.hidden = true;
        }

        return;
    }

    window.requestAnimationFrame(frame);
})();

document.addEventListener("DOMContentLoaded", function () {

    const profileBtn = document.getElementById("profileBtn");
    const profileDropdown = document.getElementById("profileDropdown");

    if (profileBtn && profileDropdown) {

        profileBtn.addEventListener("click", function (event) {
            event.stopPropagation();
            profileDropdown.classList.toggle("show");
        });

        profileDropdown.addEventListener("click", function (event) {
            event.stopPropagation();
        });

        document.addEventListener("click", function () {
            profileDropdown.classList.remove("show");
        });

    }

    const foodSearch = document.getElementById("foodSearch");
    const popularCards = document.querySelectorAll(".popular-card");
    const searchEmpty = document.getElementById("searchEmpty");

    if (foodSearch && popularCards.length) {

        foodSearch.addEventListener("input", function () {

            const text = this.value.toLowerCase().trim();
            let visible = 0;

            popularCards.forEach(function (card) {

                const match = card.dataset.name.includes(text);

                card.style.display = match ? "" : "none";

                if (match) {
                    visible++;
                }

            });

            if (searchEmpty) {
                searchEmpty.hidden = visible !== 0;
            }

        });

    }

});

document.addEventListener("DOMContentLoaded", function () {
    const foodSearch = document.getElementById("foodSearch");
    const popularCards = document.querySelectorAll(".popular-card");
    const searchEmpty = document.getElementById("searchEmpty");

    if (!foodSearch) return;

    foodSearch.addEventListener("input", function () {
        const searchValue = foodSearch.value.trim().toLowerCase();
        let visibleCount = 0;

        popularCards.forEach(function (card) {
            const foodName = card.getAttribute("data-name") || "";

            if (foodName.includes(searchValue)) {
                card.style.display = "";
                visibleCount++;
            } else {
                card.style.display = "none";
            }
        });

        // Show a message if no food matches
        if (searchEmpty) {
            searchEmpty.hidden = visibleCount !== 0;
        }
    });
});
