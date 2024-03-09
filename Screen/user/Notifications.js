import React from "react";
import { View, FlatList, Text } from "react-native";
import { LinearGradient } from "expo-linear-gradient";

const Notifications = () => {
  // Tạo một mảng gồm 20 phần tử từ 0 đến 19
  const data = Array.from(Array(5).keys());

  // Hàm render mỗi dòng của danh sách
  const renderItem = ({ item }) => (
    <LinearGradient
      locations={[0.05, 0.17, 0.8, 1]}
      colors={["#3B21B7", "#8B64DA", "#D195EE", "#CECBD3"]}
      style={{ padding: 10, borderBottomWidth: 1, borderBottomColor: "#ccc" }}
    >
      <View>
        <Text>New </Text>
      </View>

      <Text style={{ color: "white" }}>{`Item ${item}`}</Text>
    </LinearGradient>
  );

  return (
    <FlatList
      data={data}
      renderItem={renderItem}
      keyExtractor={(item) => item.toString()}
    />
  );
};

export default Notifications;
