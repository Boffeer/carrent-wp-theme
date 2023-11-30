window.addEventListener("DOMContentLoaded", (event) => {

   const getField = (fieldLabel) => document.querySelector(`[name="carbon_fields_compact_input[_${fieldLabel}]"]`);

  setTimeout(() => {
    const fetchButton = document.createElement('button');
    fetchButton.classList.add('components-button', 'is-primary');
    fetchButton.innerText = 'Get rentprog data';
    fetchButton.type = 'button';

    const rentprogElement = document.querySelector('#rentprog_api');
    const RENTPROG_API = rentprogElement.innerText;
    const TOKEN = `company_token=${RENTPROG_API}`;

    const API_URL = `https://rentprog.pro/api/v1/public`;
    fetchButton.addEventListener('click', async (e) => {
      e.preventDefault();

      let response = await fetch(`${API_URL}/get_token?${TOKEN}`, {
        method: "GET",
      });

      try {
        let {token} = await response.json();

        let carsResponse = await fetch(`${API_URL}/all_cars_full`, {
          method: "GET",
          headers: {
            'Authorization': token,
          },
        });
        let cars = await carsResponse.json();
        const CURRENT_CAR_ID = document.querySelector('[name="carbon_fields_compact_input[_rentprog_id]"]').value;
        let currentCar = cars.filter(car => car.id == CURRENT_CAR_ID)[0];

        currentCar = Object.keys(currentCar).map(key => {
          return { key: key, value: currentCar[key] };
        });

        currentCar.forEach((stat) => {
          const field= getField(stat.key);
          if (!field) return;

          if (stat.key === 'prices') {
            // console.log(stat.value)
            stat.value = stat.value[0].values.map(price => price).join(',');
            // console.log(stat)
          }

          field.value = stat.value;
        })


      } catch (error) {
        console.warn(error)
      }

    });

    const buttonWrap = document.createElement('div');
    buttonWrap.classList.add('cf-field');
    buttonWrap.append(fetchButton)

    const apiSection = document.querySelector('[name="carbon_fields_compact_input[_car_name]"]').closest('.cf-container__fields');
    if (apiSection) {
      apiSection.prepend(buttonWrap);
    }
  }, 1000)
});
