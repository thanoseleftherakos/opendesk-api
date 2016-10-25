![Image of Yaktocat](http://dev.webf8.net/opendesk_logo_blue.png)
# opendesk API
API for opendesk the opensource hotel reservation managment system. 
The API is build using Lumen Framework

## API Reference
base url: http://dev.webf8.net/hotelapi/public

##### User (not protected)
HTTP method  | Endpoint      | Req. Params    | Resp. Params   | Function  
------------ | ------------- | ------------- | ------------- | -------------
GET          | /auth/login   | email (string), password (string) | token (string) | User login

##### User (protected, provide token in headers)
HTTP method  | Endpoint      | Req. Params    | Resp. Params   | Function  
------------ | ------------- | ------------- | ------------- | -------------
GET          | /user   |  | user model | User model
PUT          | /user   | name(string), email(string), old_password, new_password, new_password_again | user model | Update User

##### Hotel (protected, provide token in headers)
HTTP method  | Endpoint      | Req. Params    | Resp. Params   | Function  
------------ | ------------- | ------------- | ------------- | -------------
GET          | /init    |  | hotel, user models | Hotel and owner info
GET          | /hotelinfo    |  | hotel, total_reservations, arivals_today_count, arivals_today, departures_today_count, departures_today,total_earnings, available_rooms_today, current_rooms, chart
GET          | /settings    |  | hotel model | Hotel settings
POST          | /settings    | name(string), email(string), total_rooms(int), logo(file), room_types (array -> id, name, amount) | error, success with hotel model | Hotel settings

##### Reservations (protected, provide token in headers)
HTTP method  | Endpoint      | Req. Params    | Resp. Params   | Function  
------------ | ------------- | ------------- | ------------- | -------------
GET          | /reservations   |  | reservations model | Fetch all hotel reservation
POST          | /reservations   | client_name, client_email, client_phone, country, check_in, check_out, room_type_id, persons, breakfast(boolean), deposit(boolean), deposit_amount, price, channel_id, ref_id, status_id, room_number, notes | Json(message, data(reservation id)) | Create reservation
PUT          | /reservations/{id}   | client_name, client_email, client_phone, country, check_in, check_out, room_type_id, persons, breakfast(boolean), deposit(boolean), deposit_amount, price, channel_id, ref_id, status_id, room_number, notes | Json(message, data(reservation id)) | Update reservation
POST          | /reservations/check_availability   | check_in, check_out, room_type_id | reservations model | Check availability for specific dates
GET          | /reservations/{id}   |  | reservation model | Get single reservation
DELETE          | /reservations/{id}   |  | error,success | Delete single reservation
POST          | /reservations/search   | stay_from, stay_to, query(string), type(arr_date, rs_date, dp_date)  | reservations model | Search reservations



### License

[MIT license](http://opensource.org/licenses/MIT)
