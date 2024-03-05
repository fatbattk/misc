#!/bin/bash
# https://github.com/waydabber/BetterDisplay/wiki/Integration-features,-CLI
# https://github.com/waydabber/BetterDisplay/discussions/2570
#
# Example usage for mode 3
#   ./toggle_monitors.sh 3

mode=$1
filename="$(basename $BASH_SOURCE)";
if [ -z "$mode" ]; then
    echo "Please provide desired mode (1, 2, 3)"
    echo "Usage: ./$filename <mode>";
    exit 1
fi

if [ "$mode" != "1" ] && [ "$mode" != "2" ] && [ "$mode" != "3" ]; then
    echo "Invalid mode. Please provide a valid mode argument (1, 2, 3)"
    exit 1
fi

if ! command -v betterdisplaycli &> /dev/null; then
    echo "betterdisplaycli command not found. Please make sure BetterDisplay and betterdisplaycli are installed."
    echo "See: https://github.com/waydabber/betterdisplaycli"
    exit 1
fi

# monitor partial names.
LG="LG"
PA="PA"
# ddc values. found from BetterDisplay input source list.
USB_C_LG=209
DISPLAY_PORT_1=15
HDMI_LG=144
HDMI_1=17

error_message() {
    echo "Failed to set $1"
}

case $mode in
    1)
        betterdisplaycli set -namelike=$LG -vcp=inputSelectAlt -ddcAlt=$USB_C_LG || error_message $LG
        # (NOTE: Does not work but leaving in anyway).
        betterdisplaycli set -namelike=$PA -vcp=inputSelect -ddc=$DISPLAY_PORT_1 || error_message $PA
        echo "Done setting mode $mode"
        ;;
    2)
        betterdisplaycli set -namelike=$LG -vcp=inputSelectAlt -ddcAlt=$HDMI_LG || error_message $LG
        # (NOTE: Removing cause only DisplayPort is connected to hub).
        # betterdisplaycli set -namelike=$PA -vcp=inputSelect -ddc=$HDMI_1 || error_message $PA
        echo "Done setting mode $mode"
        ;;
    3)
        betterdisplaycli set -namelike=$LG -vcp=inputSelectAlt -ddcAlt=$HDMI_LG || error_message $LG
        betterdisplaycli set -namelike=$PA -vcp=inputSelect -ddc=$DISPLAY_PORT_1 || error_message $PA
        echo "Done setting mode $mode"
        ;;
esac
