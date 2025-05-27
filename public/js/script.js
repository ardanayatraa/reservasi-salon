document.addEventListener("DOMContentLoaded", () => {
  // Mobile Menu Toggle
  const menuToggle = document.getElementById("menu-toggle")
  const mobileMenu = document.getElementById("mobile-menu")

  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", () => {
      mobileMenu.classList.toggle("hidden")
    })
  }

  // Back to Top Button
  window.addEventListener("scroll", () => {
    var backToTopButton = document.querySelector('a[href="#home"]')
    if (document.documentElement.scrollTop > 300) {
      backToTopButton.classList.remove("hidden")
    } else {
      backToTopButton.classList.add("hidden")
    }
  })

  // Booking Modal
  const modal = document.getElementById("booking-modal")
  const bookButtons = document.querySelectorAll(".book-service-btn")
  const closeBtn = document.querySelector(".modal-close")
  const progressBar = document.getElementById("progress-bar")
  const steps = document.querySelectorAll(".step")
  const stepContents = document.querySelectorAll(".step-content")
  const bookingForm = document.getElementById("booking-form")
  const selectedServicesContainer = document.getElementById("selected-services-container")

  // Service Data
  let bookingData = {
    services: [], // Array untuk menyimpan layanan yang dipilih
    date: new Date().toISOString().split("T")[0], // Tanggal hari ini sebagai default
    time: null,
    totalPrice: 0,
    totalDuration: 0,
  }

  // Function untuk cek ketersediaan real-time
  async function checkTimeSlotAvailability(date, time, totalDuration) {
    try {
      const response = await fetch("/check-availability", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          date: date,
          start_time: time,
          duration: totalDuration,
        }),
      })

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      return data
    } catch (error) {
      console.error("Error checking availability:", error)
      return { available: false, employees: [], slots_available: 0 }
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
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-medium">${service.name}</span>
                        <span class="text-sm text-gray-500 ml-2">(${service.duration} menit)</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-primary mr-4">Rp ${Number.parseInt(service.price).toLocaleString("id-ID")}</span>
                        <button type="button" class="remove-selected-service text-red-500 text-xl leading-none"
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
  })

  // Time slot selection dengan availability checking
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
      // Cek ketersediaan
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
            employeeInfo.innerHTML = `
                        <div class="text-sm text-green-600 mt-2 p-3 bg-green-50 rounded-md">
                            <i class="fas fa-check-circle"></i>
                            ${availability.slots_available} karyawan tersedia untuk waktu ini
                            <div class="text-xs mt-1">
                                Karyawan: ${availability.employees.map((emp) => emp.name).join(", ")}
                            </div>
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

      console.error("Error checking availability:", error)
      alert("Terjadi kesalahan saat mengecek ketersediaan. Silakan coba lagi.")
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
