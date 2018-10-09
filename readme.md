# Dancing with Death

Part 1:​ You will have to create a REST API for scheduling appointments to have a dance with Death. The API has to be implemented as a CRUD that keeps appointments, which will be used in part 2 by a web client.

Part 2:​ Create a web client for the API you just created.

# Instalation

composer install && composer update
npm install
npm run dev

# Dependencies

- [Laravel]
- [Guzzle Http]
- [Bootstrap]
- [jQuery]
- [mockAPI]
- [Datejs]
- [EasyCal]

# Testing

For testing purposes use php artisan serve and access to /calendar endpoint.

# Routes

php artisan route:list

+--------+-----------+-------------------------------------+----------------------+------------------------------------------------------------------------+--------------+
| Domain | Method    | URI                                 | Name                 | Action                                                                 | Middleware   |
+--------+-----------+-------------------------------------+----------------------+------------------------------------------------------------------------+--------------+
|        | GET|HEAD  | /                                   |                      | Closure                                                                | web          |
|        | GET|HEAD  | api/appointments                    | appointments.index   | App\Http\Controllers\AppointmentController@index                       | api          |
|        | POST      | api/appointments                    | appointments.store   | App\Http\Controllers\AppointmentController@store                       | api          |
|        | GET|HEAD  | api/appointments/create             | appointments.create  | App\Http\Controllers\AppointmentController@create                      | api          |
|        | GET|HEAD  | api/appointments/{appointment}      | appointments.show    | App\Http\Controllers\AppointmentController@show                        | api          |
|        | PUT|PATCH | api/appointments/{appointment}      | appointments.update  | App\Http\Controllers\AppointmentController@update                      | api          |
|        | DELETE    | api/appointments/{appointment}      | appointments.destroy | App\Http\Controllers\AppointmentController@destroy                     | api          |
|        | GET|HEAD  | api/appointments/{appointment}/edit | appointments.edit    | App\Http\Controllers\AppointmentController@edit                        | api          |
|        | GET|HEAD  | calendar                            | calendar             | App\Http\Controllers\AppointmentController@calendar                    | web          |
+--------+-----------+-------------------------------------+----------------------+------------------------------------------------------------------------+--------------+