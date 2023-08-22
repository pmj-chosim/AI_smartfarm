#include <avr/io.h>
#include <util/delay.h>

#define ledPin 2   // Pin untuk LED
#define pumpPin 3  // Pin untuk water pump
#define relayOn LOW    // Nilai untuk mengaktifkan relay
#define relayOff HIGH  // Nilai untuk mematikan relay

void setup() {
    DDRD |= (1 << ledPin) | (1 << pumpPin); // Set pin sebagai OUTPUT
    PORTD &= ~((1 << ledPin) | (1 << pumpPin)); // Matikan LED dan pump awalnya
}

int main(void) {
    setup();
    while (1) {
        // Nyalakan LED dan water pump selama beberapa detik
        PORTD |= (1 << ledPin) | (1 << pumpPin);
        _delay_ms(5000); // Tahan selama 5 detik

        // Matikan LED dan water pump
        PORTD &= ~((1 << ledPin) | (1 << pumpPin));
        _delay_ms(5000); // Tunggu selama 5 detik sebelum mengulangi
    }
    return 0;
}
