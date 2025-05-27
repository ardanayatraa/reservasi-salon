document.addEventListener("DOMContentLoaded", () => {
  // Enhanced Mobile Menu Toggle
  const menuToggle = document.getElementById("menu-toggle")
  const mobileMenu = document.getElementById("mobile-menu")

  if (menuToggle && mobileMenu) {
    // Toggle mobile menu with icon change
    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden")

      // Change hamburger icon to X when open
      const icon = menuToggle.querySelector('i')
      if (!mobileMenu.classList.contains("hidden")) {
        icon.classList.remove('fa-bars')
        icon.classList.add('fa-times')
      } else {
        icon.classList.remove('fa-times')
        icon.classList.add('fa-bars')
      }
    })

    // Close mobile menu when clicking on navigation links
    const mobileNavLinks = mobileMenu.querySelectorAll('a[href^="#"]')
    mobileNavLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault()

        // Close mobile menu
        mobileMenu.classList.add("hidden")
        const icon = menuToggle.querySelector('i')
        icon.classList.remove('fa-times')
        icon.classList.add('fa-bars')

        // Smooth scroll to target
        const targetId = this.getAttribute('href')
        const targetSection = document.querySelector(targetId)

        if (targetSection) {
          const offsetTop = targetSection.offsetTop - 80 // Account for fixed navbar
          window.scrollTo({
            top: offsetTop,
            behavior: 'smooth'
          })
        }
      })
    })

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
      if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
        if (!mobileMenu.classList.contains("hidden")) {
          mobileMenu.classList.add("hidden")
          const icon = menuToggle.querySelector('i')
          icon.classList.remove('fa-times')
          icon.classList.add('fa-bars')
        }
      }
    })

    // Close mobile menu on window resize to desktop
    window.addEventListener('resize', function() {
      if (window.innerWidth >= 768) {
        mobileMenu.classList.add("hidden")
        const icon = menuToggle.querySelector('i')
        icon.classList.remove('fa-times')
        icon.classList.add('fa-bars')
      }
    })
  }

  // Enhanced smooth scroll for all navigation links (desktop and mobile)
  const allNavLinks = document.querySelectorAll('a[href^="#"]')
  allNavLinks.forEach(link => {
    // Skip if it's already handled by mobile menu logic above
    if (mobileMenu && mobileMenu.contains(link)) return

    link.addEventListener('click', function(e) {
      e.preventDefault()
      const targetId = this.getAttribute('href')
      const targetSection = document.querySelector(targetId)

      if (targetSection) {
        const offsetTop = targetSection.offsetTop - 80 // Account for fixed navbar
        window.scrollTo({
          top: offsetTop,
          behavior: 'smooth'
        })
      }
    })
  })

  // Enhanced Back to Top Button
  const backToTopButton = document.querySelector('a[href="#home"]')
  if (backToTopButton) {
    // Initially hide the button
    backToTopButton.style.opacity = '0'
    backToTopButton.style.visibility = 'hidden'
    backToTopButton.style.transition = 'opacity 0.3s ease, visibility 0.3s ease'

    window.addEventListener("scroll", () => {
      if (document.documentElement.scrollTop > 300) {
        backToTopButton.style.opacity = '1'
        backToTopButton.style.visibility = 'visible'
      } else {
        backToTopButton.style.opacity = '0'
        backToTopButton.style.visibility = 'hidden'
      }
    })

    // Smooth scroll to top
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault()
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      })
    })
  }

  // Enhanced Responsive Booking Modal
  const modal = document.getElementById("booking-modal")
  const bookButtons = document.querySelectorAll(".book-service-btn")
  const closeBtn = document.querySelector(".modal-close")
  const progressBar = document.getElementById("progress-bar")
  const steps = document.querySelectorAll(".step")
  const stepContents = document.querySelectorAll(".step-content")
  const bookingForm = document.getElementById("booking-form")
  const selectedServicesContainer = document.getElementById("selected-services-container")

  // Make modal responsive
  function makeModalResponsive() {
    if (modal) {
      const modalContent = modal.querySelector('.modal-content')
      if (modalContent) {
        if (window.innerWidth < 768) {
          // Mobile styles
          modalContent.style.margin = '1rem'
          modalContent.style.maxWidth = 'calc(100vw - 2rem)'
          modalContent.style.width = 'calc(100vw - 2rem)'
          modalContent.style.maxHeight = 'calc(100vh - 2rem)'
          modalContent.style.overflow = 'auto'

          // Adjust stepper for mobile
          const stepper = modalContent.querySelector('.stepper')
          if (stepper) {
            stepper.style.marginBottom = '1rem'
          }

          // Adjust step labels for mobile
          const stepLabels = modalContent.querySelector('.flex.justify-between.text-sm')
          if (stepLabels) {
            stepLabels.style.fontSize = '0.75rem'
            stepLabels.style.marginBottom = '1rem'
          }

          // Adjust padding for mobile
          const modalPadding = modalContent.querySelector('.p-6')
          if (modalPadding) {
            modalPadding.classList.remove('p-6')
            modalPadding.classList.add('p-4')
          }

          // Make time slots grid responsive
          const timeSlotGrids = modalContent.querySelectorAll('.grid.grid-cols-5')
          timeSlotGrids.forEach(grid => {
            grid.classList.remove('grid-cols-5')
            grid.classList.add('grid-cols-3')
          })

          // Adjust service selection for mobile
          const serviceRows = modalContent.querySelectorAll('.service-row')
          serviceRows.forEach(row => {
            row.classList.remove('flex', 'items-center', 'space-x-2')
            row.classList.add('flex', 'flex-col', 'space-y-2')

            const select = row.querySelector('select')
            const button = row.querySelector('button')
            if (select) select.style.width = '100%'
            if (button) button.style.width = '100%'
          })

        } else {
          // Desktop styles (reset)
          modalContent.style.margin = '2rem auto'
          modalContent.style.maxWidth = '800px'
          modalContent.style.width = ''
          modalContent.style.maxHeight = ''
          modalContent.style.overflow = ''

          // Reset stepper
          const stepper = modalContent.querySelector('.stepper')
          if (stepper) {
            stepper.style.marginBottom = '2rem'
          }

          // Reset step labels
          const stepLabels = modalContent.querySelector('.flex.justify-between.text-sm')
          if (stepLabels) {
            stepLabels.style.fontSize = ''
            stepLabels.style.marginBottom = '2rem'
          }

          // Reset padding
          const modalPadding = modalContent.querySelector('.p-4')
          if (modalPadding) {
            modalPadding.classList.remove('p-4')
            modalPadding.classList.add('p-6')
          }

          // Reset time slots grid
          const timeSlotGrids = modalContent.querySelectorAll('.grid.grid-cols-3')
          timeSlotGrids.forEach(grid => {
            grid.classList.remove('grid-cols-3')
            grid.classList.add('grid-cols-5')
          })

          // Reset service selection
          const serviceRows = modalContent.querySelectorAll('.service-row')
          serviceRows.forEach(row => {
            row.classList.remove('flex', 'flex-col', 'space-y-2')
            row.classList.add('flex', 'items-center', 'space-x-2')

            const select = row.querySelector('select')
            const button = row.querySelector('button')
            if (select) select.style.width = ''
            if (button) button.style.width = ''
          })
        }
      }
    }
  }

  // Apply responsive modal on load and resize
  makeModalResponsive()
  window.addEventListener('resize', makeModalResponsive)

  // Service Data
  let bookingData = {
    services: [], // Array untuk menyimpan layanan yang dipilih
    date: new Date().toISOString().split("T")[0], // Tanggal hari ini sebagai default
    time: null,
    totalPrice: 0,
    totalDuration: 0,
  }

  // Function untuk cek ketersediaan real-time dengan fallback
  async function checkTimeSlotAvailability(date, time, totalDuration) {
    try {
      // Cek apakah route tersedia
      const response = await fetch("/check-availability", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({
          date: date,
          start_time: time,
          duration: totalDuration,
        }),
      })

      if (response.status === 405) {
        // Route tidak tersedia, gunakan fallback
        console.warn("Availability check route not available, using fallback")
        return {
          available: true,
          employees: [{ name: "Auto-assigned" }],
          slots_available: 1,
          message: "Availability check not available, assuming slot is available"
        }
      }

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return data
    } catch (error) {
      console.warn("Error checking availability, using fallback:", error)

      // Fallback: assume slot is available
      return {
        available: true,
        employees: [{ name: "Auto-assigned" }],
        slots_available: 1,
        message: "Unable to check availability, assuming slot is available"
      }
    }
  }

  // Function to set active step
  function setActiveStep(stepNumber) {
    steps.forEach((step) => step.classList.remove("active", "completed"))
    stepContents.forEach((content) => content.classList.remove("active"))

    for (let i = 0; i < stepNumber; i++) {
      steps[i].classList.add("completed")
    }

    steps[stepNumber].classList.add("active")
    stepContents[stepNumber].classList.add("active")

    const progress = (stepNumber / (steps.length - 1)) * 100
    progressBar.style.width = progress + "%"
  }

  // Function to go to step
  function goToStep(stepNumber) {
    setActiveStep(stepNumber - 1)
    // Scroll to top of modal on mobile
    if (window.innerWidth < 768 && modal) {
      const modalContent = modal.querySelector('.modal-content')
      if (modalContent) {
        modalContent.scrollTop = 0
      }
    }
  }

  // Event listeners for booking buttons
  if (bookButtons.length > 0) {
    bookButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const serviceId = this.dataset.service
        const serviceName = this.dataset.name
        const price = Number.parseInt(this.dataset.price)
        const duration = Number.parseInt(this.dataset.duration)

        // Reset booking data
        bookingData = {
          services: [
            {
              id: serviceId,
              name: serviceName,
              price: price,
              duration: duration,
            },
          ],
          date: new Date().toISOString().split("T")[0],
          time: null,
          totalPrice: price,
          totalDuration: duration,
        }

        // Update selected services display
        updateSelectedServicesDisplay()

        // Open modal
        openModal()
      })
    })
  }

  // Function to update selected services display
  function updateSelectedServicesDisplay() {
    // Clear container
    selectedServicesContainer.innerHTML = ""

    // Add each selected service
    bookingData.services.forEach((service, index) => {
      const serviceElement = document.createElement("div")
      serviceElement.className = "selected-service"
      serviceElement.innerHTML = `
                <div class="flex justify-between items-center ${window.innerWidth < 768 ? 'flex-col space-y-2' : ''}">
                    <div class="${window.innerWidth < 768 ? 'text-center' : ''}">
                        <span class="font-medium">${service.name}</span>
                        <span class="text-sm text-gray-500 ml-2">(${service.duration} menit)</span>
                    </div>
                    <div class="flex items-center ${window.innerWidth < 768 ? 'justify-center space-x-4' : ''}">
                        <span class="text-primary mr-4">Rp ${Number.parseInt(service.price).toLocaleString("id-ID")}</span>
                        <button type="button" class="remove-selected-service text-red-500 text-xl leading-none hover:bg-red-100 rounded-full w-6 h-6 flex items-center justify-center"
                            data-index="${index}" title="Hapus">&times;</button>
                    </div>
                </div>
            `
      selectedServicesContainer.appendChild(serviceElement)
    })

    // Add event listeners to remove buttons
    document.querySelectorAll(".remove-selected-service").forEach((button) => {
      button.addEventListener("click", function () {
        const index = Number.parseInt(this.dataset.index)
        bookingData.services.splice(index, 1)
        updateSelectedServicesDisplay()
        updateTotals()
        refreshSlots()
      })
    })

    // Update totals and refresh slots
    updateTotals()
    refreshSlots()
  }

  // Function to update total price and duration
  function updateTotals() {
    bookingData.totalPrice = bookingData.services.reduce((total, service) => {
      return total + Number.parseInt(service.price)
    }, 0)

    bookingData.totalDuration = bookingData.services.reduce((total, service) => {
      return total + Number.parseInt(service.duration)
    }, 0)

    document.getElementById("displayDurasi").textContent = bookingData.totalDuration
  }

  // Function to open modal
  function openModal() {
    if (!window.isLoggedIn) {
      window.location.href = "/login"
      return
    }

    if (modal) {
      modal.style.display = "block"
      document.body.style.overflow = "hidden" // Prevent scrolling
      makeModalResponsive() // Apply responsive styles
    }
  }

  // Function to close modal
  function closeModal() {
    if (modal) {
      modal.style.display = "none"
      document.body.style.overflow = "auto" // Enable scrolling
      localStorage.removeItem("bookingData")
      history.replaceState(null, "", window.location.pathname)
      resetBookingForm()
      goToStep(1)
    }
  }

  // Event listener for close button
  if (closeBtn) {
    closeBtn.addEventListener("click", closeModal)
  }

  // Event listener for closing modal when clicking outside
  if (modal) {
    window.addEventListener("click", (event) => {
      if (event.target == modal) {
        closeModal()
      }
    })
  }

  // Helper parse/format
  function parseHM(str) {
    const [h, m] = str.split(":").map(Number)
    return h * 60 + m
  }

  function formatHM(min) {
    const h = Math.floor(min / 60),
      m = min % 60
    return h.toString().padStart(2, "0") + ":" + m.toString().padStart(2, "0")
  }

  // Refresh slots based on total duration
  function refreshSlots() {
    // Enable next button if at least one service is selected
    document.getElementById("next-to-step-2").disabled = bookingData.services.length === 0

    // Update end time if a time slot is selected
    if (bookingData.time) {
      const startTime = parseHM(bookingData.time)
      const endTime = startTime + bookingData.totalDuration
      document.getElementById("displayEnd").textContent = formatHM(endTime)
    }

    // Show/hide time slots based on duration
    document.querySelectorAll(".shift-block").forEach((block) => {
      const shiftEnd = parseHM(block.dataset.end)
      block.querySelectorAll(".time-slot").forEach((slot) => {
        const start = parseHM(slot.dataset.time)
        slot.style.display = start + bookingData.totalDuration <= shiftEnd ? "block" : "none"
      })
    })
  }

  // Add service to selected services
  document.querySelectorAll(".add-to-selected").forEach((button) => {
    button.addEventListener("click", function () {
      const select = this.previousElementSibling
      if (select.value) {
        const option = select.selectedOptions[0]
        const serviceId = select.value
        const serviceName = option.dataset.name
        const price = Number.parseInt(option.dataset.price)
        const duration = Number.parseInt(option.dataset.duration)

        // Check if service already selected
        const alreadySelected = bookingData.services.some((service) => service.id === serviceId)
        if (alreadySelected) {
          alert("Layanan ini sudah dipilih!")
          return
        }

        // Add to services array
        bookingData.services.push({
          id: serviceId,
          name: serviceName,
          price: price,
          duration: duration,
        })

        // Reset select
        select.value = ""

        // Update display
        updateSelectedServicesDisplay()
      }
    })
  })

  // Add new service row
  document.getElementById("add-service-row").addEventListener("click", () => {
    const container = document.getElementById("services-container")
    const row = container.querySelector(".service-row").cloneNode(true)

    // Reset select
    row.querySelector("select").value = ""

    // Add event listener to add button
    row.querySelector(".add-to-selected").addEventListener("click", function () {
      const select = this.previousElementSibling
      if (select.value) {
        const option = select.selectedOptions[0]
        const serviceId = select.value
        const serviceName = option.dataset.name
        const price = Number.parseInt(option.dataset.price)
        const duration = Number.parseInt(option.dataset.duration)

        // Check if service already selected
        const alreadySelected = bookingData.services.some((service) => service.id === serviceId)
        if (alreadySelected) {
          alert("Layanan ini sudah dipilih!")
          return
        }

        // Add to services array
        bookingData.services.push({
          id: serviceId,
          name: serviceName,
          price: price,
          duration: duration,
        })

        // Reset select
        select.value = ""

        // Update display
        updateSelectedServicesDisplay()
      }
    })

    container.appendChild(row)
    makeModalResponsive() // Reapply responsive styles
  })

  // Time slot selection dengan availability checking dan fallback
  window.selectSlot = async (shiftId, time) => {
    // Clear previous selections
    document
      .querySelectorAll(".time-slot")
      .forEach((slot) => slot.classList.remove("selected", "unavailable", "checking"))

    const selectedSlot = document.querySelector(`.time-slot[data-time="${time}"]`)
    if (!selectedSlot) return

    // Cek apakah ada layanan yang dipilih
    if (bookingData.services.length === 0) {
      alert("Silakan pilih layanan terlebih dahulu")
      return
    }

    // Show checking state
    selectedSlot.classList.add("checking")
    selectedSlot.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
    selectedSlot.style.pointerEvents = "none"

    try {
      // Cek ketersediaan dengan fallback
      const availability = await checkTimeSlotAvailability(bookingData.date, time, bookingData.totalDuration)

      // Reset slot display
      selectedSlot.innerHTML = time
      selectedSlot.style.pointerEvents = "auto"
      selectedSlot.classList.remove("checking")

      if (availability.available) {
        // Slot tersedia
        selectedSlot.classList.add("selected")
        bookingData.time = time

        // Set hidden input value
        document.getElementById("selected-time").value = time

        // Calculate end time
        const startTime = parseHM(time)
        const endTime = startTime + bookingData.totalDuration
        document.getElementById("displayEnd").textContent = formatHM(endTime)

        // Show available employees info
        if (availability.employees && availability.employees.length > 0) {
          const employeeInfo = document.getElementById("employee-info")
          if (employeeInfo) {
            const warningMessage = availability.message ? `<div class="text-xs text-yellow-600 mt-1">${availability.message}</div>` : ''
            employeeInfo.innerHTML = `
                        <div class="text-sm text-green-600 mt-2 p-3 bg-green-50 rounded-md">
                            <i class="fas fa-check-circle"></i>
                            ${availability.slots_available} karyawan tersedia untuk waktu ini
                            <div class="text-xs mt-1">
                                Karyawan: ${availability.employees.map((emp) => emp.name).join(", ")}
                            </div>
                            ${warningMessage}
                        </div>
                    `
          }
        }

        // Enable next button
        document.getElementById("next-to-step-2").disabled = false
      } else {
        // Slot tidak tersedia
        selectedSlot.classList.add("unavailable")

        // Show unavailable info
        const employeeInfo = document.getElementById("employee-info")
        if (employeeInfo) {
          employeeInfo.innerHTML = `
                    <div class="text-sm text-red-600 mt-2 p-3 bg-red-50 rounded-md">
                        <i class="fas fa-times-circle"></i>
                        Tidak ada karyawan yang tersedia untuk waktu ini. Silakan pilih waktu lain.
                    </div>
                `
        }

        alert("Maaf, tidak ada karyawan yang tersedia untuk waktu tersebut. Silakan pilih waktu lain.")
      }
    } catch (error) {
      // Reset slot display
      selectedSlot.innerHTML = time
      selectedSlot.style.pointerEvents = "auto"
      selectedSlot.classList.remove("checking")

      console.error("Error in slot selection:", error)

      // Fallback: allow selection anyway
      selectedSlot.classList.add("selected")
      bookingData.time = time
      document.getElementById("selected-time").value = time

      const startTime = parseHM(time)
      const endTime = startTime + bookingData.totalDuration
      document.getElementById("displayEnd").textContent = formatHM(endTime)

      const employeeInfo = document.getElementById("employee-info")
      if (employeeInfo) {
        employeeInfo.innerHTML = `
          <div class="text-sm text-yellow-600 mt-2 p-3 bg-yellow-50 rounded-md">
              <i class="fas fa-exclamation-triangle"></i>
              Tidak dapat mengecek ketersediaan karyawan. Slot dipilih dengan asumsi tersedia.
          </div>
        `
      }

      document.getElementById("next-to-step-2").disabled = false
    }
  }

  // Next button for Step 1
  const nextToStep2Button = document.getElementById("next-to-step-2")
  if (nextToStep2Button) {
    nextToStep2Button.addEventListener("click", () => {
      const selectedTimeSlot = document.querySelector(".time-slot.selected")

      if (!selectedTimeSlot) {
        alert("Silakan pilih waktu booking.")
        return
      }

      if (bookingData.services.length === 0) {
        alert("Silakan pilih minimal satu layanan.")
        return
      }

      // Update summary in Step 2
      const summaryServicesList = document.getElementById("summary-services-list")
      summaryServicesList.innerHTML = ""

      bookingData.services.forEach((service) => {
        const serviceItem = document.createElement("div")
        serviceItem.className = "flex justify-between mb-2"
        serviceItem.innerHTML = `
                    <span class="text-gray-600">${service.name}:</span>
                    <span class="font-medium">Rp ${Number.parseInt(service.price).toLocaleString("id-ID")}</span>
                `
        summaryServicesList.appendChild(serviceItem)
      })

      document.getElementById("summary-date").textContent = formatDate(bookingData.date)
      document.getElementById("summary-time").textContent = bookingData.time
      document.getElementById("summary-price").textContent = formatPrice(bookingData.totalPrice)

      goToStep(2)
    })
  }

  // Back button for Step 2
  const backToStep1Button = document.getElementById("back-to-step-1")
  if (backToStep1Button) {
    backToStep1Button.addEventListener("click", () => {
      goToStep(1)
    })
  }

  // Pay button for Midtrans integration
  const payButton = document.getElementById("pay-button")
  if (payButton) {
    payButton.addEventListener("click", () => {
      // Show loading
      payButton.disabled = true
      payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...'

      // Collect data to send to server
      const formData = new FormData(bookingForm)

      // Add all selected services
      bookingData.services.forEach((service, index) => {
        formData.append(`services[${index}][id]`, service.id)
        formData.append(`services[${index}][name]`, service.name)
        formData.append(`services[${index}][price]`, service.price)
        formData.append(`services[${index}][duration]`, service.duration)
      })

      formData.append("total_price", bookingData.totalPrice)
      formData.append("booking_date", bookingData.date)
      formData.append("booking_time", bookingData.time)

      // Send data to server to get Midtrans token
      fetch("/book-service", {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Save booking data for use after payment
            localStorage.setItem(
              "bookingData",
              JSON.stringify({
                orderId: data.order_id,
                customerName: window.userData?.nama_lengkap || "",
                serviceName: bookingData.services.map((s) => s.name).join(", "),
                bookingDate: formatDate(bookingData.date),
                bookingTime: bookingData.time,
                price: bookingData.totalPrice,
                assignedEmployee: data.assigned_employee || "Auto-assigned",
              }),
            )
            // Open Midtrans Snap
            window.snap.pay(data.snap_token, {
              onSuccess: (result) => {
                window.location.href = "/payment/finish?status=success&order_id=" + data.order_id
              },
              onPending: (result) => {
                window.location.href = "/payment/finish?status=pending&order_id=" + data.order_id
              },
              onError: (result) => {
                window.location.href = "/payment/finish?status=error&order_id=" + data.order_id
              },
              onClose: () => {
                payButton.disabled = false
                payButton.innerHTML = "Bayar Sekarang"
                alert("Anda menutup popup pembayaran sebelum menyelesaikan transaksi.")
              },
            })
          } else {
            alert(data.message || "Terjadi kesalahan. Silakan coba lagi.")
            payButton.disabled = false
            payButton.innerHTML = "Bayar Sekarang"
          }
        })
        .catch((error) => {
          console.error("Error:", error)
          alert("Terjadi kesalahan. Silakan coba lagi.")
          payButton.disabled = false
          payButton.innerHTML = "Bayar Sekarang"
        })
    })
  }

  // Close booking
  const closeBookingButton = document.getElementById("close-booking")
  if (closeBookingButton) {
    closeBookingButton.addEventListener("click", closeModal)
  }

  // Helper Functions
  function resetBookingForm() {
    // Reset booking data
    bookingData = {
      services: [],
      date: new Date().toISOString().split("T")[0],
      time: null,
      totalPrice: 0,
      totalDuration: 0,
    }

    // Reset time slots
    document.querySelectorAll(".time-slot").forEach((slot) => {
      slot.classList.remove("selected", "unavailable", "checking")
      slot.innerHTML = slot.dataset.time
    })

    // Reset selected services container
    selectedServicesContainer.innerHTML = ""

    // Reset service selects
    document.querySelectorAll(".service-select").forEach((select) => {
      select.value = ""
    })

    // Remove extra service rows
    const serviceRows = document.querySelectorAll(".service-row")
    if (serviceRows.length > 1) {
      for (let i = 1; i < serviceRows.length; i++) {
        serviceRows[i].remove()
      }
    }

    // Reset display values
    document.getElementById("displayDurasi").textContent = "0"
    document.getElementById("displayEnd").textContent = "–"

    // Clear employee info
    const employeeInfo = document.getElementById("employee-info")
    if (employeeInfo) {
      employeeInfo.innerHTML = ""
    }

    // Disable next button
    document.getElementById("next-to-step-2").disabled = true
  }

  function formatPrice(price) {
    return "Rp " + price.toLocaleString("id-ID")
  }

  function formatDate(dateString) {
    const date = new Date(dateString)
    const options = {
      year: "numeric",
      month: "long",
      day: "numeric",
    }
    return date.toLocaleDateString("id-ID", options)
  }

  // Check if we need to show success message (after form submission)
  const urlParams = new URLSearchParams(window.location.search)
  const paymentStatus = urlParams.get("status")
  const orderId = urlParams.get("order_id")

  if (paymentStatus && orderId) {
    // Get booking data from localStorage
    const savedBookingData = JSON.parse(localStorage.getItem("bookingData") || "{}")

    if (paymentStatus === "success") {
      // Show success modal
      if (modal) {
        modal.style.display = "block"
        document.body.style.overflow = "hidden"

        // Go to success step
        goToStep(3)

        // Set reservation details
        document.getElementById("reservation-id").textContent = savedBookingData.orderId || orderId
        document.getElementById("reservation-name").textContent =
          savedBookingData.customerName || window.userData?.nama_lengkap || "-"
        document.getElementById("reservation-service").textContent = savedBookingData.serviceName || "-"
        document.getElementById("reservation-datetime").textContent =
          (savedBookingData.bookingDate ? savedBookingData.bookingDate + ", " : "") +
          (savedBookingData.bookingTime || "-")
        document.getElementById("reservation-price").textContent = savedBookingData.price
          ? formatPrice(savedBookingData.price)
          : "-"
      }

      // Remove data from localStorage after use
      localStorage.removeItem("bookingData")
    } else if (paymentStatus === "pending") {
      // Show pending notification
      showNotification(
        "info",
        "Pembayaran Tertunda",
        "Pembayaran Anda sedang diproses. Kami akan mengirimkan konfirmasi setelah pembayaran selesai.",
      )
    } else if (paymentStatus === "error") {
      // Show error notification
      showNotification("error", "Pembayaran Gagal", "Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.")
    }
  }

  // Function to show notifications
  function showNotification(type, title, message) {
    const notification = document.createElement("div")
    notification.id = `${type}-notification`

    let bgColor, borderColor, textColor, icon
    switch (type) {
      case "info":
        bgColor = "bg-blue-100"
        borderColor = "border-blue-500"
        textColor = "text-blue-700"
        icon = "fas fa-info-circle"
        break
      case "error":
        bgColor = "bg-red-100"
        borderColor = "border-red-500"
        textColor = "text-red-700"
        icon = "fas fa-exclamation-circle"
        break
      default:
        bgColor = "bg-gray-100"
        borderColor = "border-gray-500"
        textColor = "text-gray-700"
        icon = "fas fa-bell"
    }

    notification.className = `fixed top-20 right-4 ${bgColor} border-l-4 ${borderColor} ${textColor} p-4 rounded shadow-md z-50 max-w-sm`
    notification.innerHTML = `
      <div class="flex items-start">
          <div class="py-1 mr-3"><i class="${icon} text-xl"></i></div>
          <div class="flex-1">
              <p class="font-bold">${title}</p>
              <p class="text-sm">${message}</p>
          </div>
          <button class="ml-2 text-lg leading-none hover:opacity-70" onclick="this.parentElement.parentElement.remove()">&times;</button>
      </div>
    `
    document.body.appendChild(notification)

    // Auto-hide notification after 8 seconds
    setTimeout(() => {
      if (notification.parentElement) {
        notification.style.opacity = "0"
        notification.style.transition = "opacity 0.5s ease"
        setTimeout(() => {
          if (notification.parentElement) {
            notification.remove()
          }
        }, 500)
      }
    }, 8000)
  }
})
