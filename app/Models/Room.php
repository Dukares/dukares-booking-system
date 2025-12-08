public function reservations()
{
    return $this->hasMany(Reservation::class);
}
